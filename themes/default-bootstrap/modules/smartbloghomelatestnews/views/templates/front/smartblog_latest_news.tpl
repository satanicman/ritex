{if isset($view_data) AND !empty($view_data)}
<div class="smartbloghomelatestnews">
    {assign var='i' value=0}
    <ul class="smartbloghomelatestnews__list">
        {foreach from=$view_data item=post}
            {assign var="options" value=null}
            {$options.id_post = $post.id}
            {$options.slug = $post.link_rewrite}
            {assign var='i' value=$i+1}
            <li class="smartbloghomelatestnews__item smartbloghomelatestnews__item_num_{$i} clearfix" style="background-image: url({$modules_dir}smartblog/images/{$post.post_img}.jpg);">
                <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.title}" class="smartbloghomelatestnews__link">
                    <img src="{$modules_dir}smartblog/images/{$post.post_img}.jpg" alt="{$post.title}" class="smartbloghomelatestnews__image img-responsive">
                </a>
                <div class="smartbloghomelatestnews__description_short smartbloghomelatestnews-description smartbloghomelatestnews-description_short">
                    <h4 class="smartbloghomelatestnews-description__title">{$post.title}</h4>
                    <p class="smartbloghomelatestnews-description__text">
                        {$post.short_description|escape:'htmlall':'UTF-8'}
                    </p>
                    <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="smartbloghomelatestnews-description__link">{l s='Read More' mod='smartbloghomelatestnews'}</a>
                </div>
                <div class="smartbloghomelatestnews__description_full smartbloghomelatestnews-description smartbloghomelatestnews-description_full">
                    <h4 class="smartbloghomelatestnews-description__title">{$post.title}</h4>
                    <p class="smartbloghomelatestnews-description__text">
                        {$post.short_description|escape:'htmlall':'UTF-8'}
                    </p>
                </div>
            </li>
        {/foreach}
    </ul>
    <a href="{smartblog::GetSmartBlogLink('smartblog')}" class="smartbloghomelatestnews__link smartbloghomelatestnews__link_more">{l s='Read More...' mod='smartbloghomelatestnews'}</a>
</div>
{/if}