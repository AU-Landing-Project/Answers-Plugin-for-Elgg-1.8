<?php
/**
 * Voting area view
 */

$answer = $vars['entity'];
$question = get_question_for_answer($answer);
$user_guid = elgg_get_logged_in_user_guid();

$chosen_answer = ($question->chosen_answer == $answer->getGUID());

$count_like = answers_count_likes($answer);
$count_dislike = answers_count_dislikes($answer);

$owns_question = ($question->getOwnerGUID() == $user_guid);
$owns_answer = ($answer->getOwnerGUID() == $user_guid);
$can_rate = elgg_is_logged_in() && !$owns_answer;
if ($can_rate) {
	$user_like_dislike = answers_get_like_dislike($answer, $_SESSION['user']->getGUID());
	$user_like = ($user_like_dislike == "like");
	$user_dislike = ($user_like_dislike == "dislike");

	$ts = time();
	$token = generate_action_token($ts);
	$url_token = "&__elgg_token=$token&__elgg_ts=$ts";

	$like_url = $vars['url'] . "action/answer/like?answer_id=" . $answer->getGUID() . $url_token;
	$dislike_url = $vars['url'] . "action/answer/dislike?answer_id=" . $answer->getGUID() . $url_token;
}

?>
<div class="answers_rating_block">
<?php
if ($can_rate) {
	echo elgg_view('input/hidden', array(
			'name' => 'answer_id',
			'value' => $answer->getGUID()
		));
	echo elgg_view("answers/rating_icon", array('type' => 'like', 
												'tooltip' => $user_like ? 'unlike' : 'like', 
//												'href' => $like_url, 
												'selected' => $user_like));
}
?>
	<span class="answers_rating"><?php echo $count_like - $count_dislike; ?></span>
	<?php
	if ($can_rate) {
		echo elgg_view("answers/rating_icon", array('type' => 'dislike', 
													'tooltip' => $user_dislike ? 'unlike' : 'dislike', 
//													'href' => $dislike_url, 
													'selected' => $user_dislike));
		
		if ($owns_question && !$chosen_answer) {
			?>
			<br/>
			<a title="<?php echo elgg_echo("answers:answer:tooltip:choose"); ?>" class="answers_choose" href="<?php echo $vars['url']; ?>action/answer/choose?question_id=<?php echo $question->getGUID(); ?>&answer_id=<?php echo $answer->getGUID() . $url_token; ?>">
				<div style="background-image:url(<?php echo elgg_get_site_url(); ?>mod/answers/graphics/check_button.png);width: 40px; height:40px;"></div>
			</a>
			<?php
		}
	}
	?>
</div>