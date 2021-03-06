<?php

/**
* $Id: permission.php,v 1.4 2005/08/15 16:51:58 fx2024 Exp $
* Module: SmartFAQ
* Author: The SmartFactory <www.smartfactory.ca>
* Credits: Mithrandir
* Licence: GNU
*/

class SmartfaqPermissionHandler extends XoopsObjectHandler
{
	/*
	* Returns permissions for a certain type
	*
	* @param string $type "global", "forum" or "topic" (should perhaps have "post" as well - but I don't know)
	* @param int $id id of the item (forum, topic or possibly post) to get permissions for
	*
	* @return array
	*/
	function getPermissions($type = "category", $id = null) {
	    global $xoopsUser;
	    static $permissions;
	    
	    if (!isset($permissions[$type]) || ($id != null && !isset($permissions[$type][$id]))) {
	        $smartModule =& sf_getModuleInfo();
	        //Get group permissions handler
	        $gperm_handler =& xoops_gethandler('groupperm');
	        //Get user's groups
	        $groups = is_object($xoopsUser)? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
	        	        
	        switch ($type) {
	            case "category":
	            $gperm_name = "category_read";
	            break;
	            
	            case "item":
	            $gperm_name = "item_read";
	            break;
	            
	            case "moderation":
	            $gperm_name = "category_moderation";
	            $groups = is_object($xoopsUser)? $xoopsUser->getVar('uid') : 0;
	        }
	        
	        //Get all allowed item ids in this module and for this user's groups
	        $userpermissions =& $gperm_handler->getItemIds($gperm_name, $groups, $smartModule->getVar('mid'));
            $permissions[$type] = $userpermissions;
	    }
		//Return the permission array
		return isset($permissions[$type])? $permissions[$type] : array();
	}
}
?>
