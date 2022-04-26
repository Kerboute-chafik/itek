{

{extends file='checkout/_partials/steps/checkout-step.tpl'}

{block name='step_content'}
    <div class="custom-checkout-step">

        <form
                method="POST"
                action="{$urls.pages.order}"
                data-refresh-url="{url entity='order' params=['ajax' => 1, 'action' => 'customStep']}"
        >

            <div class="form-fields">

                            <div class="form-group col-12 col-xs-12">
                                <label class="col-md-4 form-control-label">Medical test</label>
                                <div class="col-12 col-xs-12">
                                    <label class="col-md-4 form-control-label required">groupe sanguin</label>
                                    <input type="text" name="itekorder_1"/>
                                </div>

                            </div>
            </div>
            <footer class="clearfix">
                <input type="submit" name="submitCustomStep" value="{l s='Submit' mod='itekorder'}"
                       class="btn btn-primary continue float-xs-right"/>
            </footer>
        </form>
    </div>
{/block}
