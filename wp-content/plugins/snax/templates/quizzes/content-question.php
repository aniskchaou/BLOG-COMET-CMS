<?php
$snax_question_class = array(
	'snax-quiz-question',
	'snax-quiz-question-' . get_the_ID(),
	'snax-quiz-question-hidden',
	'snax-quiz-question-unanswered',
	'snax-quiz-question-open',
	'snax-quiz-question-title-' . ( snax_get_title_hide() ? 'hide' : 'show' ),
	'snax-quiz-question-answer-title-' . ( snax_get_answers_labels_hide() ? 'hide' : 'show' ),
);
?>

<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_question_class ) ); ?>" data-quizzard-question-id="<?php echo absint( get_the_ID() ); ?>">
	<span class="snax-quiz-question-xofy">
		<?php
			echo str_replace(
				array(
					'%1$d',
					'%2$d',
				),
				array(
					'<span class="snax-quiz-question-xofy-x"></span>',
					'<span class="snax-quiz-question-xofy-y"></span>',
				),
				esc_html__( 'Question %1$d of %2$d', 'snax' )
			);
		?>
	</span>
	<span class="snax-quiz-question-progress"><span class="snax-quiz-question-progress-bar"></span></span>
	<?php the_title( '<h2 class="snax-quiz-question-title">', '</h2>' ); ?>

	<?php snax_render_quiz_question_featured_media(); ?>

	<?php snax_get_template_part('quizzes/loop-answers' ); ?>

</div><!-- .snax-quiz-question -->
