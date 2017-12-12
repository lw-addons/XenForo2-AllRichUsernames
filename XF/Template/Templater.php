<?php

namespace LiamW\AllRichUsernames\XF\Template;

use XF\App;
use XF\Language;

class Templater extends XFCP_Templater
{
	protected $displayGroupIds = [];

	public function addDefaultHandlers()
	{
		parent::addDefaultHandlers();

		$this->addFunction('liamwcopyright', function ($templater, &$escape, $addOnKwd = null) {
			$addOnKwd = $addOnKwd ?: 'Other';

			$escape = false;

			return '<xf:if is="!$lwaBrandingShown">
						<xf:set var="$lwaBrandingShown" value="1" />
						<a href="https://lw-addons.net/?pk_campaign=LWA-Branding&pk_kwd=' . $addOnKwd . '" class="u-concealed" dir="ltr" style="display: block">Certain add-on functionality by LW Addons <span class="copyright">&copy;2017 Liam Williams.</span></a>
					</xf:if>';
		});
	}

	public function fnUsernameClasses($templater, &$escape, $user, $includeGroupStyling = true)
	{
		$includeGroupStyling = true;

		if (empty($user['display_style_group_id']))
		{
			$user['display_style_group_id'] = $this->getDisplayStyleGroupIdFromCache($user['user_id']);
		}
		else
		{
			$this->displayGroupIds[$user['user_id']] = $user['display_style_group_id'];
		}

		return parent::fnUsernameClasses($templater, $escape, $user, $includeGroupStyling);
	}

	protected function getDisplayStyleGroupIdFromCache($userId)
	{
		if (!isset($this->displayGroupIds[$userId]))
		{
			$displayGroupId = \XF::db()
				->fetchOne("SELECT display_style_group_id FROM xf_user WHERE user_id=?", $userId);
			$this->displayGroupIds[$userId] = $displayGroupId;
		}

		return $this->displayGroupIds[$userId];
	}
}