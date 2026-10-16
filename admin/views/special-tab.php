<?php

/**
 * تب موارد خاص — import اکسل چندمحصولی با ستون شناسه محصول
 */

if (!defined('ABSPATH')) {
    exit;
}

$special_product_ids = Woo_Excel_Mng_Products::get_special_product_ids();
$special_products    = array();

if (!empty($special_product_ids)) {
    $special_products = wc_get_products(array(
        'include' => $special_product_ids,
        'limit'   => -1,
        'status'  => 'publish',
        'type'    => 'variable',
        'return'  => 'objects',
        'orderby' => 'title',
        'order'   => 'ASC',
    ));
}
?>

<div class="woo-excel-mng-special">
    <div class="section-header">
        <h2><?php _e('موارد خاص', 'woo-excel-mng'); ?></h2>
        <p class="description"><?php _e('آپلود یک فایل Excel مشترک برای محصولات تعریف‌شده در تنظیمات «محصولات خاص»', 'woo-excel-mng'); ?></p>
    </div>

    <?php if (empty($special_product_ids)) : ?>
        <div class="notice notice-warning">
            <p>
                <?php
                printf(
                    __('هنوز محصول خاصی انتخاب نشده است. از %s بخش «محصولات خاص» را پر کنید.', 'woo-excel-mng'),
                    '<a href="' . esc_url(admin_url('admin.php?page=woo-excel-mng-settings&tab=settings')) . '">' . esc_html__('تنظیمات', 'woo-excel-mng') . '</a>'
                );
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="upload-section">
        <div class="upload-box">
            <h3><?php _e('آپلود فایل Excel موارد خاص', 'woo-excel-mng'); ?></h3>
            <p class="help-text"><?php _e('فرمت فایل باید شامل ستون‌های زیر باشد:', 'woo-excel-mng'); ?></p>
            <ul class="excel-format-list">
                <li><strong><?php _e('شناسه محصول', 'woo-excel-mng'); ?></strong> - <?php _e('شناسه عددی محصول متغیر در ووکامرس', 'woo-excel-mng'); ?></li>
                <li><strong><?php _e('طول', 'woo-excel-mng'); ?></strong></li>
                <li><strong><?php _e('رنگ', 'woo-excel-mng'); ?></strong></li>
                <li><strong><?php _e('ضخامت', 'woo-excel-mng'); ?></strong> - <?php _e('مثلاً 0/25 (همان‌طور که در اکسل است)', 'woo-excel-mng'); ?></li>
                <li><strong><?php _e('وزن (کیلوگرم)', 'woo-excel-mng'); ?></strong></li>
                <li><strong><?php _e('قیمت پایه', 'woo-excel-mng'); ?></strong></li>
            </ul>

            <div class="wem-import-root" data-import-mode="special">
                <div class="wem-upload-area" style="display: flex; justify-content: center; flex-direction: column; align-items: center;">
                    <div style="width: 100%; max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label><?php _e('انتخاب فایل اکسل:', 'woo-excel-mng'); ?></label></th>
                                <td>
                                    <input type="file" class="wem-products-file" accept=".xlsx,.xls" />
                                    <p class="wem-file-name file-name" style="margin-top:8px;color:#666;"></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php _e('انتخاب محصولات:', 'woo-excel-mng'); ?></label></th>
                                <td>
                                    <select class="wem-selected-products wem-select2" multiple="multiple" style="width: 100%; min-height: 150px;" required>
                                        <?php
                                        foreach ($special_products as $product) {
                                            echo '<option value="' . esc_attr($product->get_id()) . '">' . esc_html($product->get_name()) . ' (ID: ' . $product->get_id() . ')</option>';
                                        }
                                        ?>
                                    </select>
                                    <p class="description"><?php _e('فقط محصولاتی که در تنظیمات به‌عنوان «محصولات خاص» تیک خورده‌اند.', 'woo-excel-mng'); ?></p>
                                </td>
                            </tr>
                        </table>

                        <p class="submit" style="text-align: center;">
                            <button type="button" class="wem-start-import button button-primary" disabled style="padding: 10px 30px; font-size: 16px;">
                                <?php _e('شروع پردازش (صف)', 'woo-excel-mng'); ?>
                            </button>
                        </p>
                    </div>
                </div>

                <div class="wem-progress-area" style="display:none;">
                    <div class="wem-progress-header">
                        <span class="wem-progress-icon wem-progress-icon">⏳</span>
                        <span class="wem-progress-label"><?php _e('در حال آماده‌سازی...', 'woo-excel-mng'); ?></span>
                    </div>

                    <div class="wem-current-product" style="display:none;">
                        <strong><?php _e('محصول جاری:', 'woo-excel-mng'); ?></strong>
                        <span class="wem-current-product-name">—</span>
                        <span class="wem-variation-hint"></span>
                    </div>

                    <div class="wem-progress-bar-wrap">
                        <div class="wem-progress-bar-bg">
                            <div class="wem-progress-fill wem-progress-bar-fill" style="width:0%;">
                                <span class="wem-progress-percent wem-progress-percent">۰٪</span>
                            </div>
                        </div>
                    </div>

                    <div class="wem-progress-stats">
                        <div class="wem-stat-group">
                            <div class="wem-stat-box wem-stat-done">
                                <span class="wem-stat-num wem-stat-done">۰</span>
                                <span class="wem-stat-lbl"><?php _e('محصول انجام‌شده', 'woo-excel-mng'); ?></span>
                            </div>
                            <div class="wem-stat-box wem-stat-remaining-box">
                                <span class="wem-stat-remaining wem-stat-num">—</span>
                                <span class="wem-stat-lbl"><?php _e('باقی‌مانده', 'woo-excel-mng'); ?></span>
                            </div>
                            <div class="wem-stat-box wem-stat-total-box">
                                <span class="wem-stat-total wem-stat-num">۰</span>
                                <span class="wem-stat-lbl"><?php _e('کل محصولات', 'woo-excel-mng'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="wem-mini-log"></div>
                </div>

                <div class="wem-result-area"></div>
            </div>
        </div>
    </div>

    <div class="info-box">
        <h4><?php _e('نکات مهم:', 'woo-excel-mng'); ?></h4>
        <ul>
            <li><?php _e('یک فایل شامل همه محصولات خاص است؛ برای هر محصول انتخاب‌شده فقط ردیف‌های با همان شناسه محصول اعمال می‌شود.', 'woo-excel-mng'); ?></li>
            <li><?php _e('واریانت‌های قبلی محصول انتخاب‌شده حذف و از نو ساخته می‌شوند.', 'woo-excel-mng'); ?></li>
            <li><?php _e('ضخامت به‌صورت متن (مثل 0/25) ذخیره می‌شود؛ وزن با جداکننده / به‌درستی تبدیل می‌شود.', 'woo-excel-mng'); ?></li>
        </ul>
    </div>
</div>
