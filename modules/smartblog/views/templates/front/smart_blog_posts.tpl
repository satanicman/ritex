<h3 class="title"><span>{$blg_title}</span></h3>
<div id="smartblogcat" class="block {$blg_class}">
{foreach from=$postcategory item=post}
	<article class=" single_blog_post cat_post p_bottom_20 m_bottom_30 clearfix 
	col-sm-{$per_column}" id="smartblogpost-{$post.id_post}">
 	{assign var="options" value=null}
    {$options.id_post = $post.id_post}
    {$options.slug = $post.link_rewrite}
   	{assign var="catlink" value=null}
    {$catlink.id_category = $post.id_category}
    {$catlink.slug = $post.cat_link_rewrite}
		<figure class="post_thumbnail m_bottom_20">
			<a itemprop="url" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="imageFeaturedLink">
              		<img itemprop="image" alt="{$post.meta_title}" src="{$modules_dir}/smartblog/images/{$post.post_img}-{$image_type}.jpg" class="img-responsive">
          	</a>
          	<div class="blog_mask">
          		<div class="mask_content">
          			<a itemprop="url" title="{$post.meta_title}" href="{$modules_dir}/smartblog/images/{$post.post_img}.jpg" class="post_lightbox"><i class="icon-resize-full"></i></a>
          			<a itemprop="url" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="imageFeaturedLink"><i class="icon-link"></i></a>
          		</div>
          	</div>
		</figure>
		<h3 class="post_title m_bottom_0"><a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>{$post.meta_title}</a></h3>
		<div class="post_meta m_bottom_30">
			<span class="post_meta_date"><label>{l s='Posted on' mod='smartblog'}</label> <a itemprop="url" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.created|date_format:"%B %e, %Y"} <label>{l s='at' mod='smartblog'}</label> {$post.created|date_format:"%r"}</a></span>
		  	<span itemprop="author"><label>{l s='by ' mod='smartblog'}</label> {$post.firstname}  {$post.lastname}</span>
		  	<span itemprop="articleSection"><label>{l s='/' mod='smartblog'}</label> <a href="{smartblog::GetSmartBlogLink('smartblog_category',$catlink)}">{$post.cat_name}</a></span>
		  	<span><label>{l s='/' mod='smartblog'}</label>{l s=' views' mod='smartblog'} ({$post.viewed})</span>
		</div>       
		<div class="blog_post_details m_bottom_20">
		{$post.short_description}
		</div>
		<div class="blog_post_read_more f_right">
        	{assign var="options" value=null}
    		{$options.id_post = $post.id_post}  
    		{$options.slug = $post.link_rewrite} 
     			<a class="button" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.meta_title}">{l s='Read more' mod='smartblog'}</a>
		</div>
	</article> 
{/foreach}
</div>