{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}

function Counter() {
    this.count = 0;
    this.total = 0;
    this.stepCount = 0;
    this.step = 0;
}

Counter.prototype = {
    setTotal: function(total) {
        this.total = total;
    },
    upCount: function(count) {
        this.count += count;
    },
    setStep: function(step) {
        this.step = step;
    },
    setStepCount: function(stepCount) {
        this.stepCount = stepCount;
    }
};

$(document).ready(function(){
    $(document).on('submit', 'form', function(e) {
        var formData = new FormData(),
            products = $('#products')[0].files[0],
            prices = $('#prices')[0].files[0],
            excel = $('#excel')[0].files[0],
            category = $('#AJAXIMPORT_CATEGORY').val(),
            nbr = $('#AJAXIMPORT_NBR_PRODUCTS').val(),
            countObject = new Counter();

        if(products || prices || excel)
            e.preventDefault();
        else
            return true;

        if(typeof products !== 'undefined')
            formData.append('products', products);
        if(typeof prices !== 'undefined')
            formData.append('prices', prices);
        if(typeof excel !== 'undefined')
            formData.append('excel', excel);
        formData.append('AJAXIMPORT_CATEGORY', category);
        formData.append('AJAXIMPORT_NBR_PRODUCTS', nbr);

        importFile(formData, countObject);
    });


    function importFile(formData, object) {
        console.log('run');
        formData.append('step_count', object.stepCount);
        formData.append('step', object.step);

        $.ajax({
            url : '/modules/ajaximport/ajaximport-ajax.php',
            type : 'POST',
            data : formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success : function(data) {
                if(data.answer) {
                    object.setTotal(data.total);
                    object.upCount(data.count);
                    object.setStep(data.step);
                    object.setStepCount(data.step_count);

                    $('#progress').prepend(data.answer);
                    $('#progressbar').attr('max', object.total);
                    $('#progressbar').val(object.count);

                    if(object.count < object.total)
                        importFile(formData, object);
                }
            }
        });

        return object;
    }
});
{/block}

{block name="input"}
    {if $input.type == 'progress'}
	    <div class="row">
            <div class="col-lg-12">
                <div id="{$input.name}" class="disabled"></div>
                {*<textarea name="{$input.name}" id="{$input.name}" cols="30" rows="10" class="form-control" disabled="disabled"></textarea>*}
                <progress max="100" value="0" style="width: 100%; height: 20px; margin-top: -1px;" id="progressbar"></progress>
            </div>
	    </div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
