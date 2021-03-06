/* global jQuery */
/* global document */
/* global snax_quizzes */
/* global snax_quiz_config */
/* global JSON */
/* global console */

if ( typeof window.snax_quizzes === 'undefined' ) {
    window.snax_quizzes = {};
}

/**
 * Helpers.
 */
(function($, ctx) {

    ctx.shuffleArray = function(array) {
        var currentIndex = array.length;
        var randomIndex;
        var tempValue;

        while (currentIndex > 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            // And swap it with the current element.
            tempValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = tempValue;
        }

        return array;
    };

    ctx.createCookie = function (name, value, days) {
        var expires;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        else {
            expires = '';
        }

        document.cookie = name.concat('=', value, expires, '; path=/');
    };

    ctx.readCookie = function (name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');

        for(var i = 0; i < ca.length; i += 1) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1,c.length);
            }

            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length,c.length);
            }
        }

        return null;

    };

    ctx.deleteCookie = function (name) {
        ctx.createCookie(name, '', -1);
    };

})(jQuery, snax_quizzes);

/**
 * Handle quiz actions.
 */
(function ($, ctx) {

    'use strict';

    // Quiz object.
    var quiz;

    // Quiz DOM element.
    var $quiz;

    // CSS classes.
    var QUESTION_ANSWERED       = 'snax-quiz-question-answered';
    var QUESTION_UNANSWERED     = 'snax-quiz-question-unanswered';
    var QUESTION_OPEN           = 'snax-quiz-question-open';
    var QUESTION_CLOSED         = 'snax-quiz-question-closed';
    var QUESTION_REVEAL_ANSWERS = 'snax-quiz-question-reveal-answers';
    var QUESTION_HIDDEN         = 'snax-quiz-question-hidden';
    var ANSWER_CHECKED          = 'snax-quiz-answer-checked';
    var ANSWER_RIGHT            = 'snax-quiz-answer-right';
    var ANSWER_WRONG            = 'snax-quiz-answer-wrong';
    var PAGINATION_LOCKED       = 'g1-arrow-disabled';

    var bindEvents = function() {
        quizStartedAction();
        questionAnsweredAction();
        nextPageAction();

        quiz.on('questionAnswered', function(questionId, answerId) {
            var $question = $quiz.find('.snax-quiz-question-' + questionId);
            var $answer   = $quiz.find('.snax-quiz-answer-' + answerId);

            // Update states.
            $question.removeClass(QUESTION_UNANSWERED).addClass(QUESTION_ANSWERED);
            $answer.addClass(ANSWER_CHECKED);

            // Reveal answers.
            if ('immediately' === quiz.revealCorrectWrongAnswers()) {
                revealCorrectWrongAnswers($question);
            }

            // Has pagination?
            if ( ! quiz.getQuestionsPerPage() ) {
                return;
            }

            // Enable pagination.
            var questionsOnPage   = quiz.getActiveQuestions();
            var questionsPerPage = quiz.getQuestionsPerPage();
            var answered = 0;
            var currentQuestionId;

            for (var i = 0; i < questionsOnPage.length; i++) {
                currentQuestionId = questionsOnPage[i];

                var $question = $quiz.find('.snax-quiz-question-' + currentQuestionId);

                if (!$question.is('.snax-quiz-question-hidden') && quiz.wasQuestionAnswered(currentQuestionId)) {
                    answered++;
                }
            }

            // All answered?
            if (answered === questionsPerPage) {
                $quiz.find('.snax-quiz-pagination .g1-arrow-disabled').removeClass('g1-arrow-disabled');
            }
        });

        quiz.on('questionAnswerRemoved', function(questionId, answerId) {
            var $question = $quiz.find('.snax-quiz-question-' + questionId);
            var $answer   = $quiz.find('.snax-quiz-answer-' + answerId);

            // Update states.
            $question.removeClass(QUESTION_ANSWERED).addClass(QUESTION_UNANSWERED);
            $answer.removeClass(ANSWER_CHECKED);
        });

        quiz.on('started', function () {
            $quiz.find('.snax-quiz-with-start-trigger').removeClass('snax-quiz-with-start-trigger');

            $quiz.find('.snax-quiz-questions-item').each(function() {
                showAfterQuestionContent( $(this) );
            });
        });

        quiz.on('ended', function (html, locked) {
            if (locked) {
                $('.snax-quiz-actions-hidden').removeClass('snax-quiz-actions-hidden');
                $('.snax-quiz-results-hidden').removeClass('snax-quiz-results-hidden');
            } else {
                showResults(html);
            }
        });

        quiz.on('unlocked', function(html) {
            showResults(html);
        });
    };

    var quizStartedAction = function() {
        $quiz.find('.snax-quiz-button-start-quiz').on('click', function(e) {
            e.preventDefault();

            quiz.start();
        });
    };

    var questionAnsweredAction = function() {
        $quiz.find('.snax-quiz-question').on('click', function(e) {
            e.preventDefault();

            var $question = $(this);

            var $answer = $(e.target).parents('.snax-quiz-answer');

            // Proceed only if user clicked in answer.
            if (!$answer.is('.snax-quiz-answer')) {
                return;
            }

            var answerId    = parseInt($answer.attr('data-quizzard-answer-id'), 10);
            var questionId  = parseInt($question.attr('data-quizzard-question-id'), 10);

            if (quiz.wasQuestionAnswered(questionId)) {
                if (quiz.completed()) {
                    return;
                }

                // Can't answer again if the correct answer was already revealed.
                if ('immediately' === quiz.revealCorrectWrongAnswers()) {
                    return;
                }

                quiz.removeAnswer(questionId);
            }

            quiz.addAnswer(questionId, answerId);
        });
    };

    var nextPageAction = function() {
        $quiz.find('.snax-quiz-pagination-next').on('click', function(e) {
            if ($(this).hasClass(PAGINATION_LOCKED)) {
                e.preventDefault();
            }

        });
    };

    var showResults = function(html) {
        $quiz.find('.snax-quiz-results').html(html);
        $('.snax-quiz-actions-hidden').removeClass('snax-quiz-actions-hidden');
        $('.snax-quiz-results-hidden').removeClass('snax-quiz-results-hidden');

        if ('quiz-end' === quiz.revealCorrectWrongAnswers()) {
            $quiz.find('.snax-quiz-question').each(function() {
                revealCorrectWrongAnswers($(this));
            });
        }

        if (quiz.getQuestionsPerPage()) {
            var $questions = $quiz.find('.snax-quiz-questions-wrapper').clone();

            // Calculate xofy.
            var $questionItems = $questions.find('.snax-quiz-questions-item');
            var total = $questionItems.length;

            $questionItems.each(function (index) {
                var $question = $(this);

                $question.find('.snax-quiz-question-xofy-y').html(total);

                // Calculate progress.
                var percentage  = (index + 1) / total * 100;
                $question.find('.snax-quiz-question-progress-bar').width( percentage + '%' );
            });

            // Show all answered answers.
            $questions.find('.' + QUESTION_ANSWERED).removeClass(QUESTION_HIDDEN);

            // Append and show correct answers.
            $quiz.find('.snax-quiz-check-answers').
                append($questions).
                removeClass('snax-quiz-check-answers-hidden');
        }

        $quiz.trigger('resultsShown');
    };

    var revealCorrectWrongAnswers = function($question) {
        var questionId      = parseInt($question.attr('data-quizzard-question-id'), 10);
        var correctAnswerId = quiz.getCorrectAnswer(questionId);
        var userAnswerId    = quiz.getAnswer(questionId);
        var $userAnswer     = $question.find('.snax-quiz-answer-'+ userAnswerId);

        if (quiz.isCorrectAnswer(questionId, userAnswerId)) {
            $userAnswer.addClass(ANSWER_RIGHT);
        } else {
            $userAnswer.addClass(ANSWER_WRONG);

            // Select correct.
            $question.find('.snax-quiz-answer-'+ correctAnswerId).addClass(ANSWER_RIGHT);
        }

        $question.addClass(QUESTION_REVEAL_ANSWERS);

        // Close question. User can't change it anymore.
        $question.removeClass(QUESTION_OPEN).addClass(QUESTION_CLOSED);
    };

    var showAfterQuestionContent = function( $question ) {
        // Show content only if the question is visible.
        if ( ! $question.find('.snax-quiz-question').is(':visible') ) {
            return;
        }

        var $afterContent = $question.find('.snax-after-quiz-question-content');

        // Load only non empty content.
        if ( $afterContent.length > 0 && $afterContent.val().length > 0 ) {
            $afterContent.replaceWith($afterContent.val());
        }
    };

    ctx.initQuiz = function () {
        $quiz = $('.snax > .quiz').parent();

        $quiz.addClass('snax-share-object');

        if ($quiz.length === 0) {
            return;
        }

        // Create quiz object.
        quiz = new ctx.Quiz($.parseJSON(snax_quiz_config));

        // Store reference.
        $quiz.data('quizzardShareObject', quiz);

        var questionIds = quiz.getActiveQuestions();    // It can be just a subset of all questions (shuffle: on and questions per quiz: < all questions).
        var questions = [];                             // Array of DOM objects representing questions.

        $.each(questionIds, function(index, id) {
            var $question = $quiz.find('.snax-quiz-question-' + id);
            questions.push($question.parent());

            if (quiz.shuffleAnswers()) {
                // Get all question's answers.
                var answers = $question.find('.snax-quiz-answers-item');

                // Shuffle them.
                ctx.shuffleArray(answers);

                // Reorder answers in DOM.
                $question.find('.snax-quiz-answers-items').append(answers);
            }
        });

        // Reorder questions in DOM.
        $quiz.find('.snax-quiz-questions-items').html(questions);

        var total = questions.length;

        let page = quiz.getPage();
        let minQuestionIndex = 0;
        let maxQuestionIndex = 9999;

        let questionsPerPage = quiz.getQuestionsPerPage();

        if (questionsPerPage > 0) {
            minQuestionIndex = (page - 1) * questionsPerPage;
            maxQuestionIndex = minQuestionIndex + questionsPerPage - 1;
        }

        $.each(questions, function(index, $question) {
            if (index < minQuestionIndex || index > maxQuestionIndex) {
                return;
            }

            // Calculate xofy.
            $question.find('.snax-quiz-question-xofy-y').html( total );

            // Calculate progress.
            var percentage  = (index + 1) / total * 100;
            $question.find('.snax-quiz-question-progress-bar').width( percentage + '%' );

            // Show.
            $question.find('.snax-quiz-question').removeClass(QUESTION_HIDDEN);

            showAfterQuestionContent( $question );
        });


        bindEvents();

        quiz.initAnswers();
    };

    // Init.
    $(document).ready(function() {
        ctx.initQuiz();
    });

})(jQuery, snax_quizzes);

/**
 * Define Quiz class.
 */
(function($, ctx) {

    'use strict';

    ctx.Quiz = function(options) {
        var obj = {};
        var defaults = {
            debug: false
        };

        var currentPage;
        var activeQuestions;
        var answeredQuestions;
        var correctAnswers;
        var answers;
        var events;
        var locked;
        var correct_answers = {};

        // Constructor.
        var init = function () {
            options = $.extend(defaults, options);

            for (var i = 0; i < options.questions_answers_arr.length; i++) {
                var item = options.questions_answers_arr[i];

                correct_answers[item.question_id] = item.answer;
            }

            log(options);

            currentPage = options.page;
            locked      = options.share_to_unlock;

            // Register default callbacks.
            events = {
                'started':               function() {},
                'ended':                 function() {},
                'unlocked':              function() {},
                'questionAnswered':      function() {},
                'questionAnswerRemoved': function() {}
            };

            return obj;
        };

        obj.initAnswers = function() {
            correctAnswers      = 0;    // Number of correct answers.
            answers             = {};   // Answer list (question id => answer id).
            answeredQuestions   = 0;    // Number of question that were already answered.

            var storedAnswers = readFromLocalStorage('answers');

            if (null !== storedAnswers) {
                for (var questionId in storedAnswers) {
                    var answerId = storedAnswers[questionId];

                    obj.addAnswer(questionId, answerId);
                }

                log('State updated.');
                log('Answered questions: ' + answeredQuestions);
                log('Correct answers: ' + correctAnswers);
            }

            // Hide "Let's Play" button.
            if (1 === currentPage) {
                var questionsOnPage   = obj.getActiveQuestions();
                var questionsPerPage = obj.getQuestionsPerPage();
                var answered = 0;
                var currentQuestionId;

                for (var i = 0; i < questionsPerPage; i++) {
                    currentQuestionId = questionsOnPage[i];

                    if (obj.wasQuestionAnswered(currentQuestionId)) {
                        answered++;
                    }
                }

                // All answered?
                if (answered === questionsPerPage) {
                    obj.start();
                }
            }

            // User is not allowed to access that page.
            if (currentPage > 1 && !answeredQuestions) {
                window.location.href = options.quiz_canonical_url;
            }
        };

        obj.getActiveQuestions = function() {
            // When we shuffle questions, we need to keep the same state over all pages.
            var storeLocally = obj.shuffleQuestions() && obj.getQuestionsPerPage();

            if (storeLocally) {
                activeQuestions = readFromLocalStorage('active_questions');
            }

            if (!activeQuestions) {
                log('Build final quiz question list');
                activeQuestions = [];

                // All questions, in original order.
                for ( var i = 0; i < options.questions_answers_arr.length; i++ ) {
                    var item = options.questions_answers_arr[i];

                    activeQuestions.push(item.question_id);
                }

                log('Active questions');
                log(activeQuestions);

                if (obj.shuffleQuestions()) {
                    ctx.shuffleArray(activeQuestions);

                    log('Shuffled questions');
                    log(activeQuestions);

                    if (-1 !== options.questions_per_quiz) {
                        limitQuestions();

                        log('Limited questions');
                        log(activeQuestions);
                    }
                }

                if (storeLocally) {
                    addToLocalStorage('active_questions', activeQuestions);
                }
            }

            return activeQuestions;
        };

        obj.addAnswer = function(questionId, answerId) {
            answeredQuestions++;

            answers[questionId] = answerId;

            if (obj.isCorrectAnswer(questionId, answerId)) {
                correctAnswers++;
            }

            log('Question ' + questionId + ' answered (answer ' + answerId + ').');
            log('Answered questions: ' + answeredQuestions);
            log('Stored answer: ' + answers[questionId]);

            if (obj.getQuestionsPerPage()) {
                addToLocalStorage('answers', answers);
            }

            if (obj.completed()) {
                log('Quiz ended.');

                if (obj.isLocked()) {
                    events.ended('', true);
                } else {
                    loadResults(function(html) {
                        events.ended(html, false);
                    });
                }

                resetLocalStorage();
            }

            events.questionAnswered(questionId, answerId);
        };

        obj.removeAnswer = function(questionId) {
            // Update counters.
            answeredQuestions--;

            var currentAnswerId = answers[questionId];

            if (obj.isCorrectAnswer(questionId, currentAnswerId)) {
                correctAnswers--;
            }

            // Remove answer.
            delete answers[questionId];

            log('Question '+ questionId +' answer ' + currentAnswerId + ' removed.');
            log('Answered questions: ' + answeredQuestions);
            log('Stored answer: ' + (answers[questionId] ? answers[questionId] : 'none'));

            events.questionAnswerRemoved(questionId, currentAnswerId);
        };

        obj.start = function() {
            events.started();
        };

        obj.unlock = function(verificationOk) {
            if (!obj.isLocked()) {
                log('Skip. Quiz in not locked.');
                return;
            }

            if (!verificationOk) {
                return;
            }

            log('Unlock quiz.');

            locked = false;

            loadResults(function(html) {
                events.unlocked(html);
            });
        };

        obj.getAnswer = function(questionId) {
            return answers[questionId];
        };

        obj.getCorrectAnswer = function(questionId) {
            return correct_answers[questionId];
        };

        obj.revealCorrectWrongAnswers = function() {
            return options.reveal_correct_wrong_answers;
        };

        obj.shuffleQuestions = function() {
            return options.shuffle_questions;
        };

        obj.shuffleAnswers = function() {
            return options.shuffle_answers;
        };

        obj.getQuestionsPerPage = function() {
            return parseInt(options.questions_per_page, 10);
        };

        obj.isCorrectAnswer = function(questionId, answerId) {
            return answerId === correct_answers[questionId];
        };

        obj.on = function(eventName, callback) {
            events[eventName] = callback;
        };

        obj.getScore = function(type) {
            var correct = correctAnswers;
            var all     = activeQuestions.length;
            var score   = '';

            switch(type) {
                case 'percentage':
                    score = Math.round(correct / all * 100);
                    break;
            }

            return score;
        };

        obj.isLocked = function() {
            return locked;
        };

        obj.wasQuestionAnswered = function(questionId) {
            return typeof answers[questionId] !== 'undefined';
        };

        obj.completed = function() {
            return answeredQuestions === activeQuestions.length;
        };

        obj.getPage = function() {
            return currentPage;
        };

        var limitQuestions = function() {
            var questionsLimit = Math.min(options.questions_per_quiz, options.all_questions);

            if (questionsLimit !== activeQuestions.length) {
                activeQuestions.splice(questionsLimit, activeQuestions.length - questionsLimit);
            }
        };

        var loadResults = function(callback) {
            log('Load results.');

            var xhr = $.ajax({
                'type': 'POST',
                'url': options.ajax_url,
                'dataType': 'json',
                'data': {
                    'action':       'snax_load_quiz_result',
                    'quiz_id':      options.quiz_id,
                    'answers':      answers,
                    'summary':      options.share_description
                }
            });

            xhr.done(function (res) {
                if (res.status === 'success') {
                    callback(res.args.html);
                }
            });
        };

        var readFromLocalStorage = function(id) {
            log('Reading "'+ id +'" from local storage');

            // Build final var id.
            id = 'snax_quiz_'+ options.quiz_id + '_' + id;

            var value = ctx.readCookie(id);

            if (value !== null) {
                value = $.parseJSON(value);
            }

            log('Value: ');
            log(value);

            return value;
        };

        var addToLocalStorage = function(id, value, days) {
            log('Adding "'+ id +'" to local storage');
            log(value);

            // Build final var id.
            id = 'snax_quiz_'+ options.quiz_id + '_' + id;

            days = days || 1;

            ctx.createCookie(id, JSON.stringify(value), days);
        };

        var resetLocalStorage = function() {
            ctx.deleteCookie('snax_quiz_' + options.quiz_id + '_active_questions');
            ctx.deleteCookie('snax_quiz_' + options.quiz_id + '_answers');
        };

        var log = function(data) {
            if (inDebugMode() && typeof console !== 'undefined') {
                console.log(data);
            }
        };

        var inDebugMode = function() {
            return options.debug;
        };

        return init();
    };

})(jQuery, snax_quizzes);
