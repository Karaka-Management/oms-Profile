<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

echo $this->data['nav']->render();
?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <form id="fProfileCreate" method="PUT" action="<?= \phpOMS\Uri\UriFactory::build('{/api}profile?{?}&csrf={$CSRF}'); ?>">
                <div class="portlet-head"><?= $this->getHtml('CreateProfile'); ?></div>
                <div class="portlet-body">
                    <div class="form-group">
                        <label for="iAccount"><?= $this->getHtml('Account'); ?></label>
                        <?= $this->getData('accGrpSelector')->render('iAccount', ''); ?>
                    </div>
                </div>
                <div class="portlet-foot">
                    <input type="submit" value="<?= $this->getHtml('Create', '0', '0'); ?>" name="create-module">
                </div>
            </form>
        </section>
    </div>
</div>