<?php
/**
 * Trivia settings template part
 *
 * @package snax 1.11
 * @subpackage Forms
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$snax_questions_per_page 		    = snax_get_quiz_setting( 'questions_per_page' );
$snax_shuffle_questions 			= snax_get_quiz_setting( 'shuffle_questions' );
$snax_questions_per_quiz 			= snax_get_quiz_setting( 'questions_per_quiz' );
$snax_shuffle_answers 				= snax_get_quiz_setting( 'shuffle_answers' );
$snax_start_quiz 					= snax_get_quiz_setting( 'start_quiz' );
$snax_play_again 					= snax_get_quiz_setting( 'play_again' );
$snax_share_to_unlock 				= snax_get_quiz_setting( 'share_to_unlock' );
?>

<table class="form-table">
	<tbody>
        <!-- Questions per page? -->
        <tr>
            <th>
                <label for="snax_questions_per_page">
                    <?php esc_html_e( 'Questions per page?', 'snax' ); ?>
                </label>
            </th>
            <td>
                <input type="text" id="snax_questions_per_page" name="snax_questions_per_page" value="<?php echo esc_attr( $snax_questions_per_page ); ?>" />
                <p class="description">
                    <?php esc_html_e( 'Leave empty to show all questions.', 'snax' ); ?>
                </p>
            </td>
        </tr>

		<!-- Shuffle questions? -->
		<tr>
			<th>
				<label for="snax_shuffle_questions">
					<?php esc_html_e( 'Shuffle questions?', 'snax' ); ?>
				</label>
			</th>
			<td>
				<select id="snax_shuffle_questions" name="snax_shuffle_questions">
					<option value="standard"<?php selected( $snax_shuffle_questions, 'standard' ); ?>><?php esc_html_e( 'yes', 'snax' ); ?></option>
					<option value="none"<?php selected( $snax_shuffle_questions, 'none' ); ?>><?php esc_html_e( 'no', 'snax' ); ?></option>
				</select>
			</td>
		</tr>

		<!-- Questions per quiz -->
		<tr>
			<th>
				<label for="snax_questions_per_quiz">
					<?php esc_html_e( 'Questions per quiz', 'snax' ); ?>
				</label>
			</th>
			<td>
				<input type="number" id="snax_questions_per_quiz" name="snax_questions_per_quiz" value="<?php echo esc_attr( $snax_questions_per_quiz ); ?>" />
				<p class="description">
					<?php esc_html_e( 'Leave empty to show all available questions.', 'snax' ); ?>
					<?php esc_html_e( 'Works only with the "Shuffle questions" options enabled.', 'snax' ); ?>
				</p>
			</td>
		</tr>


		<!-- Shuffle answers? -->
		<tr>
			<th>
				<label for="snax_shuffle_answers">
					<?php esc_html_e( 'Shuffle answers?', 'snax' ); ?>
				</label>
			</th>
			<td>
				<select id="snax_shuffle_answers" name="snax_shuffle_answers">
					<option value="standard"<?php selected( $snax_shuffle_answers, 'standard' ); ?>><?php esc_html_e( 'yes', 'snax' ); ?></option>
					<option value="none"<?php selected( $snax_shuffle_answers, 'none' ); ?>><?php esc_html_e( 'no', 'snax' ); ?></option>
				</select>
			</td>
		</tr>

		<!-- Start quiz button -->
		<tr>
			<th>
				<label for="snax_start_quiz">
					<?php esc_html_e( '"Start Quiz" button?', 'snax' ); ?>
				</label>
			</th>
			<td>
				<select id="snax_start_quiz" name="snax_start_quiz">
					<option value="standard"<?php selected( $snax_start_quiz, 'standard' ); ?>><?php esc_html_e( 'yes', 'snax' ); ?></option>
					<option value="none"<?php selected( $snax_start_quiz, 'none' ); ?>><?php esc_html_e( 'no', 'snax' ); ?></option>
				</select>
			</td>
		</tr>

		<!-- Play again button -->
		<tr>
			<th>
				<label for="snax_play_again">
					<?php esc_html_e( '"Play again" button?', 'snax' ); ?>
				</label>
			</th>
			<td>
				<select id="snax_play_again" name="snax_play_again">
					<option value="standard"<?php selected( $snax_play_again, 'standard' ); ?>><?php esc_html_e( 'yes', 'snax' ); ?></option>
					<option value="none"<?php selected( $snax_play_again, 'none' ); ?>><?php esc_html_e( 'no', 'snax' ); ?></option>
				</select>
			</td>
		</tr>

		<!-- Share to unlock -->
		<tr>
			<th>
				<label for="snax_share_to_unlock">
					<?php esc_html_e( 'User has to share the quiz to see results?', 'snax' ); ?>
				</label>
			</th>
			<td>
				<select id="snax_share_to_unlock" name="snax_share_to_unlock">
					<option value="standard"<?php selected( $snax_share_to_unlock, 'standard' ); ?>><?php esc_html_e( 'yes', 'snax' ); ?></option>
					<option value="none"<?php selected( $snax_share_to_unlock, 'none' ); ?>><?php esc_html_e( 'no', 'snax' ); ?></option>
				</select>
				<?php if ( ! snax_can_share_to_unlock() ): ?>
					<small>
						<?php esc_html_e( 'Option not available.', 'snax' ); ?>
						<a href="<?php echo esc_url( get_admin_url( null, 'options-general.php?page=snax-shares-settings' ) ); ?>"><?php esc_html_e( 'Check Shares settings', 'snax' ); ?></a>
					</small>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>


