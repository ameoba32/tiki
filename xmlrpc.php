<?php 
# $Header: /cvsroot/tikiwiki/tiki/xmlrpc.php,v 1.21 2004-07-15 22:55:16 teedog Exp $
include_once("lib/init/initlib.php");
require_once('db/tiki-db.php');
require_once('lib/tikilib.php');
require_once('lib/userslib.php');
require_once("lib/xmlrpc.inc");
require_once("lib/xmlrpcs.inc");
include_once('lib/blogs/bloglib.php');
require_once ('lib/userslib/userslib_admin.php');

$tikilib = new Tikilib($dbTiki);
$userlib = new Userslib($dbTiki);
if($tikilib->get_preference("feature_xmlrpc",'n') != 'y') {
  die;  
}
$map = array (
        "blogger.newPost" => array( "function" => "newPost"),
        "blogger.getUserInfo" => array( "function" => "getUserInfo"),
        "blogger.getPost" => array( "function" => "getPost"),
        "blogger.editPost" => array( "function" => "editPost"),
        "blogger.deletePost" => array( "function" => "deletePost"),
        "blogger.getRecentPosts" => array( "function" => "getRecentPosts"),
        "blogger.getUserInfo" => array( "function" => "getUserInfo"),
        "blogger.getUsersBlogs" => array( "function" => "getUserBlogs")
        
);
$s=new xmlrpc_server( $map );

function check_individual($user,$blogid,$permName) {
  global $userlib;
  // If the user is admin he can do everything
  if($userlib->user_has_permission($user,'tiki_p_blog_admin')) return true;
  // If no individual permissions for the object then ok
  if(!$userlib->object_has_one_permission($blogid,'blog')) return true;
  // If the object has individual permissions then check
  // Now get all the permissions that are set for this type of permissions 'image gallery'
  if($userlib->object_has_permission($user,$blogId,'blog',$permName)) {
    return true;
  } else {
    return false;
  }          
}
/* Validates the user and returns user information */
function getUserInfo($params) {
 global $tikilib,$userlib;
 $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
 $usernamep=$params->getParam(1); $username=$usernamep->scalarval();
 $passwordp=$params->getParam(2); $password=$passwordp->scalarval();
 if($userslibadmin->validate_user($username,$password,'','')) {
   $myStruct=new xmlrpcval(array("nickname" => new xmlrpcval($username),
                                 "firstname" => new xmlrpcval("none"),
                                 "lastname" => new xmlrpcval("none"),
                                 "email" => new xmlrpcval("none"),
                                 "userid" => new xmlrpcval("$username"),
                                 "url" => new xmlrpcval("none")
                                 ),"struct");
   return new xmlrpcresp($myStruct);
 } else {
    return new xmlrpcresp(0, 101, "Invalid username or password");
 } 
}
 
/* Posts a new submission to the CMS */
function newPost($params) {
  global $tikilib,$userlib,$bloglib;
  $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
  $blogidp=$params->getParam(1); $blogid=$blogidp->scalarval();
  $usernamep=$params->getParam(2); $username=$usernamep->scalarval();
  $passwordp=$params->getParam(3); $password=$passwordp->scalarval();
  $passp=$params->getParam(4); $content=$passp->scalarval();
  $passp=$params->getParam(5); $publish=$passp->scalarval();
  
  // Fix for w.bloggar
  ereg("<title>(.*)</title>",$content, $title);
  $title = $title[1];
  $content = ereg_replace("<title>(.*)</title>","",$content);
  // Now check if the user is valid and if the user can post a submission
  if(!$userslibadmin->validate_user($username,$password,'','')) {
    return new xmlrpcresp(0, 101, "Invalid username or password");
  }
 
  // Get individual permissions for this weblog if they exist
  if(!check_individual($username,$blogid,'tiki_p_blog_post') ) {
    return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog");
  }
  
  // If the blog is not public then check if the user is the owner
  if(!$userlib->user_has_permission($username,'tiki_p_blog_admin')) {
    if(!$userlib->user_has_permission($username,'tiki_p_blog_post')) {
      return new xmlrpcresp(0, 101, "User is not allowed to post");
    }
    $blog_info = $tikilib->get_blog($blogid);
    if($blog_info["public"]!='y') {
      if($username != $blog_info["user"]) {
        return new xmlrpcresp(0, 101, "User is not allowed to post");
      }
    }
  }
  
  // User ok and can submit then submit the post
  $now=date("U");
  
  $id = $bloglib->blog_post($blogid,$content,$username, $title);
   
  return new xmlrpcresp(new xmlrpcval("$id"));
}
// :TODO: editPost
function editPost($params) {
  global $tikilib,$userlib,$bloglib;
  $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
  $blogidp=$params->getParam(1); $postid=$blogidp->scalarval();
  $usernamep=$params->getParam(2); $username=$usernamep->scalarval();
  $passwordp=$params->getParam(3); $password=$passwordp->scalarval();
  $passp=$params->getParam(4); $content=$passp->scalarval();
  $passp=$params->getParam(5); $publish=$passp->scalarval();
  
  // Fix for w.bloggar
  ereg("<title>(.*)</title>",$content, $title);
  $title = $title[1];
  $content = ereg_replace("<title>(.*)</title>","",$content);
  // Now check if the user is valid and if the user can post a submission
  if(!$userslibadmin->validate_user($username,$password,'','')) {
    return new xmlrpcresp(0, 101, "Invalid username or password");
  }
 
  if(!check_individual($username,$blogid,'tiki_p_blog_post') ) {
    return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog therefor the user cannot edit a post");
  }
 
  if(!$userlib->user_has_permission($username,'tiki_p_blog_post')) {
    return new xmlrpcresp(0, 101, "User is not allowed to post");
  }
  
  // Now get the post information
  $post_data = $bloglib->get_post($postid);
  if(!$post_data) {
    return new xmlrpcresp(0, 101, "Post not found");
  }
  
  if($post_data["user"]!=$username) {
    if(!$userlib->user_has_permission($username,'tiki_p_blog_admin')) {
      return new xmlrpcresp(0, 101, "Permission denied to edit that post since the post does not belong to the user");
    }
  }
 
  $now=date("U");
  $id = $bloglib->update_post($postid,$blogid,$content,$username,$title);
  return new xmlrpcresp(new xmlrpcval(1,"boolean"));
}
// :TODO: deletePost
function deletePost($params) {
  global $tikilib,$userlib,$bloglib;
  $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
  $blogidp=$params->getParam(1); $postid=$blogidp->scalarval();
  $usernamep=$params->getParam(2); $username=$usernamep->scalarval();
  $passwordp=$params->getParam(3); $password=$passwordp->scalarval();
  $passp=$params->getParam(4); $publish=$passp->scalarval();
  // Now check if the user is valid and if the user can post a submission
  if(!$userslibadmin->validate_user($username,$password,'','')) {
    return new xmlrpcresp(0, 101, "Invalid username or password");
  }
 
  
  // Now get the post information
  $post_data = $bloglib->get_post($postid);
  if(!$post_data) {
    return new xmlrpcresp(0, 101, "Post not found");
  }
      
  if($post_data["user"]!=$username) {
    if(!$userlib->user_has_permission($username,'tiki_p_blog_admin')) {
      return new xmlrpcresp(0, 101, "Permission denied to edit that post");
    }
  }
 
  
  $now=date("U");
  $id = $bloglib->remove_post($postid);
  return new xmlrpcresp(new xmlrpcval(1,"boolean"));
}
// :TODO: getTemplate
// :TODO: setTemplate
// :TODO: getPost
function getPost($params) {
  global $tikilib,$userlib,$bloglib;
  $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
  $blogidp=$params->getParam(1); $postid=$blogidp->scalarval();
  $usernamep=$params->getParam(2); $username=$usernamep->scalarval();
  $passwordp=$params->getParam(3); $password=$passwordp->scalarval();
  // Now check if the user is valid and if the user can post a submission
  if(!$userslibadmin->validate_user($username,$password,'','')) {
    return new xmlrpcresp(0, 101, "Invalid username or password");
  }
  if(!check_individual($username,$blogid,'tiki_p_blog_post') ) {
    return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog");
  }
 
  if(!$userlib->user_has_permission($username,'tiki_p_blog_post')) {
    return new xmlrpcresp(0, 101, "User is not allowed to post");
  }
  if(!$userlib->user_has_permission($username,'tiki_p_read_blog')) {
      return new xmlrpcresp(0, 101, "Permission denied to read this blog");
  }
  
  // Now get the post information
  $post_data = $bloglib->get_post($postid);
  if(!$post_data) {
    return new xmlrpcresp(0, 101, "Post not found");
  }
#  $dateCreated=date("Ymd",$post_data["created"])."T".date("h:i:s",$post_data["created"]);
  $dateCreated=$tikilib->get_iso8601_datetime($post_data["created"]);    
  // added dateTime type for blogger compliant xml tag Joerg Knobloch <joerg@happypenguins.net>
  $myStruct=new xmlrpcval(array("userid" => new xmlrpcval($username),
"dateCreated" => new xmlrpcval($dateCreated, "dateTime.iso8601"),
// Fix for w.Bloggar
"content" => new xmlrpcval("<title>" . $post_data["title"] . "</title>" . $post_data["data"]),
"postid" => new xmlrpcval($post_data["postId"])
),"struct");
  
 
  // User ok and can submit then submit an article
  
  return new xmlrpcresp($myStruct);
}
// :TODO: getRecentPosts
function getRecentPosts($params) {
  global $tikilib,$userlib,$bloglib;
  $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
  $blogidp=$params->getParam(1); $blogid=$blogidp->scalarval();
  $usernamep=$params->getParam(2); $username=$usernamep->scalarval();
  $passwordp=$params->getParam(3); $password=$passwordp->scalarval();
  $passp=$params->getParam(4); $number=$passp->scalarval();
  // Now check if the user is valid and if the user can post a submission
  if(!$userslibadmin->validate_user($username,$password,'','')) {
    return new xmlrpcresp(0, 101, "Invalid username or password");
  }
  
  if(!check_individual($username,$blogid,'tiki_p_blog_post') ) {
    return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog therefore the user cannot edit a post");
  }
  
  if(!$userlib->user_has_permission($username,'tiki_p_blog_post')) {
    return new xmlrpcresp(0, 101, "User is not allowed to post");
  }
  
  // Now get the post information
  $posts = $bloglib->list_blog_posts($blogid, 0, $number,'created_desc', '', '');
  if(count($posts)==0) {
    return new xmlrpcresp(0, 101, "No posts");
  }
  $arrayval = Array();
  foreach($posts["data"] as $post) {
    
#    $dateCreated=date("Ymd",$post["created"])."T".date("h:i:s",$post["created"]);    
    $dateCreated=$tikilib->get_iso8601_datetime($post["created"]);    
    $myStruct=new xmlrpcval(array("userid" => new xmlrpcval($username),
  "dateCreated" => new xmlrpcval($dateCreated, "dateTime.iso8601"),
  // Fix for w.Bloggar
  "content" => new xmlrpcval("<title>" . $post["title"] . "</title>" . $post["data"]),
  "postid" => new xmlrpcval($post["postId"])
  ),"struct");
    $arrayval[]=$myStruct;
  }  
 
  // User ok and can submit then submit an article
  
 $myVal=new xmlrpcval($arrayval, "array");
 return new xmlrpcresp($myVal);
}
// :TODO: tiki.tikiPost
/* Get the topics where the user can post a new */
function getUserBlogs($params) {
 global $tikilib,$userlib,$bloglib;
 $appkeyp=$params->getParam(0); $appkey=$appkeyp->scalarval();
 $usernamep=$params->getParam(1); $username=$usernamep->scalarval();
 $passwordp=$params->getParam(2); $password=$passwordp->scalarval();
 
 $arrayVal=Array();
 
	global $bloglib;
	if (!is_object($bloglib)) {
		include_once('lib/blogs/bloglib.php');
	}
 $blogs = $bloglib->list_user_blogs($username,true);
 $foo = parse_url($_SERVER["REQUEST_URI"]);
 $foo1=httpPrefix().str_replace("xmlrpc","tiki-view_blog",$foo["path"]);
 foreach($blogs as $blog) {
   $myStruct=new xmlrpcval(array("blogName" => new xmlrpcval($blog["title"]),
                               "url" => new xmlrpcval($foo1."?blogId=".$blog["blogId"]),
                               "blogid" => new xmlrpcval($blog["blogId"])),"struct");
   $arrayVal[] = $myStruct;                              
 }
 
 $myVal=new xmlrpcval($arrayVal, "array");
 return new xmlrpcresp($myVal);
}
?>
