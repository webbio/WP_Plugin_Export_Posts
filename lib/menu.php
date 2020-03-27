<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('Menu') ) :

class Menu {
    function __construct() {

    }

    public function getMenu($menu_id) {
        $blogId = get_current_blog_id();
        $menu = $this->getMenuItems($menu_id);

        return array(
            '_id' => $blogId . '-menu-' . $menu_id,
            'blogId' => $blogId,
            'type' => 'menu',
            'data' => array(
                'menu' => $menu
            )
        );
    }

    private function getMenuItems($nav_menu_selected_id) {
        $rawMenuItems = wp_get_nav_menu_items($nav_menu_selected_id);
        $refinedMenuItems = array_map(array($this, 'trimObject'), $rawMenuItems);
        $finalMenu = array();
        foreach ($refinedMenuItems as $elementKey => $element) {
            if($element['parent'] != "0"){
                $result = $this->findItem($finalMenu, $element['parent'], $element);
            } else {
                array_push($finalMenu, $element);
            }
        }
        return $finalMenu;
    }
    
    private function trimObject($menuItemObj){
        $obj['id'] = $menuItemObj->db_id;
        $obj['title'] = $menuItemObj->title;
        $obj['post_title'] = $menuItemObj->post_title;
        $obj['post_name'] = $menuItemObj->post_name;
        $obj['url'] = $menuItemObj->url;
        $obj['order'] = $menuItemObj->menu_order;
        $obj['parent'] = $menuItemObj->menu_item_parent;
        $obj['child_items'] = array();
        return $obj;
    }

    private function findItem(&$array, $itemId, $child){
        foreach($array as $elementKey => $element) {
            if((int)$element['id'] == (int)$itemId){
                array_push($array[$elementKey]['child_items'], $child);
                return true;
            } else if ($element['child_items']) {
                $result = $this->findItem($array[$elementKey]['child_items'], $itemId, $child);
                if($result) {
                    return $result;
                }
            }
        }
        return false;
    }
}

endif;