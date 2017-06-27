{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}
$(document).ready(function(){
$('.menuOrderUp').click(function(e){
	e.preventDefault();
    move(true, $(this));
});
$('.menuOrderDown').click(function(e){
    e.preventDefault();
    move(false, $(this));
});
$(".items_line").closest('form').on('submit', function(e) {
	$(".items_line option").prop('selected', true);
});
$(".addItem").click(add);
$(".availableItems").dblclick(add);
$(".removeItem").click(remove);
$(".items").dblclick(remove);
function add()
{
	var items_line = $(this).closest('.items_line');
	var items = items_line.find('.items');
	var availableItems = items_line.find('.availableItems');
	console.log($(this).length);
	availableItems.find("option:selected").each(function(i){
		var val = $(this).val();
		var text = $(this).text();
		text = text.replace(/(^\s*)|(\s*$)/gi,"");
		items.append('<option value="'+val+'" selected="selected">'+text+'</option>');
	});
	return false;
}
function remove()
{
	$(this).closest('.items_line').find(".items option:selected").each(function(i){
		$(this).remove();
	});
	return false;
}
function move(up, that)
{
        var tomove = that.closest('.items_line').find('.items option:selected');
        if (tomove.length >1)
        {
                alert('{l s="Please select just one item" mod='homepagefilter'}');
                return false;
        }
        if (up)
                tomove.prev().insertAfter(tomove);
        else
                tomove.next().insertBefore(tomove);
        return false;
}
});
{/block}

{block name="input"}
    {if $input.type == 'category_choice'}
	    <div class="row">
			{$choices_category}
	    </div>
    {elseif $input.type == 'feature_choice'}
	    <div class="row items_line">
	    	<div class="col-lg-2">
	    		<h4 style="margin-top:5px;">{l s='Change position' mod='homepagefilter'}</h4>
                <a href="#" class="menuOrderUp btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-up"></i></a><br/>
                <a href="#" class="menuOrderDown btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-down"></i></a><br/>
	    	</div>
	    	<div class="col-lg-5">
	    		<h4 style="margin-top:5px;">{l s='Selected items' mod='homepagefilter'}</h4>
	    		{$selected_links_feature}
	    	</div>
	    	<div class="col-lg-5">
	    		<h4 style="margin-top:5px;">{l s='Available items' mod='homepagefilter'}</h4>
	    		{$choices_feature}
	    	</div>

			<div class="clearfix"></div>
			<br/>
			<div class="clearfix">
				<div class="col-lg-1"></div>
				<div class="col-lg-4"><a href="#" class="removeItem btn btn-default"><i class="icon-arrow-right"></i> {l s='Remove' mod='homepagefilter'}</a></div>
				<div class="col-lg-4"><a href="#" class="addItem btn btn-default"><i class="icon-arrow-left"></i> {l s='Add' mod='homepagefilter'}</a></div>
			</div>
	    </div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
