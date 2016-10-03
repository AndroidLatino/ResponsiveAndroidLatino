<?php
function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Here's where the "Info Center" starts...
	echo '
						<div class="card">
						<aside class="card-side pull-left"> <i class="material-icons">info</i> </aside>
							<div class="card-main">
							<div classs="card-header"><h3>Panel de Información</h3></div>
								<nav class="tab-nav tab-nav-brand margin-top-no">
									<ul class="nav nav-justified">
										<li class="active">', empty($options['collapse_header_ic']) ? '' : ' style="display: none;"', '>';

	// This is the "Recent Posts" bar.
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{
		echo '
				
					<div class="row">
						<div class="col-xs-12">
							<h4 class="sinmargen"><a href="', $scripturl,'?action=recent" ><span class="icon">note</span></a>', $txt['recent_posts'],  '</h4>				
						</div>

				
		';
		// Only show one post.
		if ($settings['number_recent_posts'] == 1)
		{
			// latest_post has link, href, time, subject, short_subject (shortened with...), and topic. (its id.)
			echo '
						<div class="col-xs-12">
							', $txt['recent_view'], ' &quot;', $context['latest_post']['link'], '&quot; ', $txt['recent_updated'], ' (', $context['latest_post']['time'], ')
						 </div>
				';
		}
		// Show lots of posts.
		elseif (!empty($context['latest_posts']))
		{
			/* Each post in latest_posts has:
					board (with an id, name, and link.), topic (the topic's id.), poster (with id, name, and link.),
					subject, short_subject (shortened with...), time, link, and href. */
			echo' 
						<div class="col-xs-12">';
			foreach ($context['latest_posts'] as $post)
				echo '
							<div>
								<span class="icon">fiber_new</span><b>', $post['link'], '</b> ', $txt['by'], ' ', $post['poster']['link'], ' (', $post['board']['link'], ') <span class="pull-right visible-md-block visible-lg-block">', $post['time'], '</span>
							</div>';
			echo '
						</div>';
		}


		echo '
					</div>
							
		';

	}

	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
					<div class="row">
						<div class="col-xs-12">
							<div >
								<a href="\', $scripturl, \'?action=calendar\' . \'"><span class="icon">date_range</span> </a>
							</div>
							<small>';
		// Holidays like "Christmas", "Chanukah", and "We Love [Unknown] Day" :P.
		if (!empty($context['calendar_holidays']))
			echo '
								<p class="holiday">\', $txt[\'calendar_prompt\'], \' \', implode(\', \', $context[\'calendar_holidays\']), \'</p>';
		// People's birthdays. Like mine. And yours, I guess. Kidding.
		if (!empty($context['calendar_birthdays']))
		{
			echo '
								<span class="birthday">\', $context[\'calendar_only_today\'] ? $txt[\'birthdays\'] : $txt[\'birthdays_upcoming\'], \'</span>
			';
			/* Each member in calendar_birthdays has:
				id, name (person), age (if they have one set?), is_last. (last in list?), and is_today (birthday is today?) */
			foreach ($context['calendar_birthdays'] as $member)
				echo '
								<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong>' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '<br />' : ', ';
		}
		// Events like community get-togethers.
		if (!empty($context['calendar_events']))
		{
			echo '
								<span class="event">', $context['calendar_only_today'] ? $txt['events'] : $txt['events_upcoming'], '</span> ';
			/* Each event in calendar_events should have:
					title, href, is_last, can_edit (are they allowed?), modify_href, and is_today. */
			foreach ($context['calendar_events'] as $event)
				echo '
							', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" title="' . $txt['calendar_edit'] . '"><img src="' . $settings['images_url'] . '/icons/modify_small.gif" alt="*" /></a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br />' : ', ';
		}
		echo '
							</small>
						</div>
					</div>';
	}

	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo '
					<div class="row">
						<div class="col-xs-12">
							<h4 class="sinmargen">
								<a href="', $scripturl, '?action=stats"><span class="icon">assessment</span></a>
								', $txt['forum_stats'], '
							</h4>
							<p>
								', $context['common_stats']['total_posts'], ' ', $txt['posts_made'], ' ', $txt['in'], ' ', $context['common_stats']['total_topics'], ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'], ' ', $txt['members'], '. ', !empty($settings['show_latest_member']) ? $txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong>' : '', '<br />
								', (!empty($context['latest_post']) ? $txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;</strong>  ( ' . $context['latest_post']['time'] . ' )<br />' : ''), '
								<a href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a>', $context['show_stats'] ? '<br />
								<a href="' . $scripturl . '?action=stats">' . $txt['more_stats'] . '</a>' : '', '							
							</p>
						</div>
					</div>
					</div>
		';
	}

	// "Users online" - in order of activity.
	echo '
					<div class="row">
						<div class="col-xs-12">
							<h4 class="sinmargen">
								', $context['show_who'] ? '<a href="' . $scripturl . '?action=who' . '">' : '', '<span class="icon">people</span>', $context['show_who'] ? '</a>' : '', '
								', $txt['online_users'], '
							</h4>
							<p>
				', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

	// Handle hidden users and buddies.
	$bracketList = array();
	if ($context['show_buddies'])
		$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
	if (!empty($context['num_spiders']))
		$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
	if (!empty($context['num_users_hidden']))
		$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . $txt['hidden'];

	if (!empty($bracketList))
		echo ' (' . implode(', ', $bracketList) . ')';

	echo $context['show_who'] ? '</a>' : '', '
							</p>
							<small>';

	// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
	if (!empty($context['users_online']))
	{
		echo '
			', sprintf($txt['users_active'], $modSettings['lastActive']), ':<br />', implode(', ', $context['list_users_online']);

		// Showing membergroups?
		if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '
							<br />[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']';
	}
	echo '
							</small>
							<small>
								', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
								', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
							</small>
						</div>
					</div>';

	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
					<div class="row">
						<div class="col-xs-12">
							<h4 class="sinmargen">
								', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<span class="icon" >message</span>', $context['allow_pm'] ? '</a>' : '', '
								<span>', $txt['personal_message'], '</span>
							</h4>
							<p>
								<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
								<small>
									', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
								</small>
							</p>
						</div>
					</div>
		';
	}


?>