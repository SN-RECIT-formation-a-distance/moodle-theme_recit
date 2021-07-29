<?php

class ThemRecitUtils2
{
    public static function getUserRoles($courseId, $userId){
        // get the course context (there are system context, module context, etc.)
        $context = context_course::instance($courseId);

        return ThemRecitUtils2::getUserRolesOnContext($context, $userId);
    }

    public static function getUserRolesOnContext($context, $userId){
        $userRoles1 = get_user_roles($context, $userId);

        $userRoles2 = array();
        foreach($userRoles1 as $item){
            $userRoles2[] = $item->shortname;
        }

        $ret = self::moodleRoles2RecitRoles($userRoles2);

        if(is_siteadmin($userId)){
            $ret[] = 'ad';
        }
        
        return $ret;
    }
    
    public static function moodleRoles2RecitRoles($userRoles){
        $ret = array();

        foreach($userRoles as $name){
            switch($name){
                case 'manager': $ret[] = 'mg'; break;
                case 'coursecreator': $ret[] = 'cc'; break;
                case 'editingteacher': $ret[] = 'et'; break;
                case 'teacher': $ret[] = 'tc'; break;
                case 'student': $ret[] = 'sd'; break;
                case 'guest': $ret[] = 'gu'; break;
                case 'frontpage': $ret[] = 'fp'; break;
            }
        }

        return $ret;
    }
    
    public static function isAdminRole($roles){
        $adminRoles = array('ad', 'mg', 'cc', 'et', 'tc');

        if(empty($roles)){ return false;}

        foreach($roles as $role){
            if(in_array($role, $adminRoles)){
                return true;
            }
        }
        return false;
    }
}