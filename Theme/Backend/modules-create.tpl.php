<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Template
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

echo $this->getData('nav')->render();
?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="box wf-100">
            <header><h1><?= $this->getHtml('CreateProfile'); ?></h1></header>

            <div class="inner">
                <form id="fProfileCreate" method="PUT" action="<?= \phpOMS\Uri\UriFactory::build('{/api}profile?{?}&csrf={$CSRF}'); ?>">
                    <table class="layout wf-100" style="table-layout: fixed">
                        <tbody>
                        <tr><td><label for="iAccount"><?= $this->getHtml('Account'); ?></label>
                        <tr><td><?= $this->getData('accGrpSelector')->render('iAccount', true); ?>
                        <tr><td><input type="submit" value="<?= $this->getHtml('Create', '0', '0'); ?>" name="create-module">
                    </table>
                </form>
            </div>
        </section>
    </div>
</div>