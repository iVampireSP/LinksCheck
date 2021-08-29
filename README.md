# LinksCheck 链接检查
## may misjudgment 可能会有误判

### 以下为WordPress 主题使用方法

1. 将以下代码放入主题functions.php中
```php
// echo links json
function get_link_items_json($id = null){
   	$bookmarks = get_bookmarks('orderby=date&category=' . $id);
    $output = [];
    if (!empty($bookmarks)) {
        foreach ($bookmarks as $bookmark) {
            $output['links'][] = ['name' => $bookmark->link_name, 'link' => $bookmark->link_url];
        }
    }
    return json_encode($output);
}
```

2. 然后在你的主题目录下新建一个文件，命名为`page-linksApi.php`，然后输入以下内容
```php
<?php
/*
Template Name: 链接 API
*/
$pageType = 2;
?>
<?php while(have_posts()):the_post();the_content();?>
<?php echo get_link_items_json()?>
<?php endwhile;?>

```


3. 最后到你的WordPress后台，新建一个页面，将页面模板改成`链接 API`

4. 访问你的新页面试试！

5. 编辑check.php，将`https://example.blog/linksjson`修改为你的页面地址
6. 
7. 推荐使用CLI运行`check.php`，比如`php check.php`，然后它将会自动检查。结果只能做为参考，因为误判率很高