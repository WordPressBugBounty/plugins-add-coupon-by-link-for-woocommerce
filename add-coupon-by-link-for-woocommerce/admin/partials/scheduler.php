<div class="p-1">
<div class="pi-display-flex">
    <input type="checkbox" name="pisol_aclw_date_based_scheduling_enabled" id="pisol_aclw_date_based_scheduling_enabled" value="yes" <?php echo !empty($date_schedule_enabled) ? 'checked' : ''; ?> class="pi-radio-group">
    <strong><?php esc_html_e('Enable date based schedule', 'add-coupon-by-link-woocommerce'); ?></strong>
</div>
<script type="text/html" id="date-schedule-row-template">
    <?php self::row_template('{count}', []); ?>
</script>

<div id="pi-date-based-scheduling-container" class="mt-2">
    <button class="button" id="pi-add-date-schedule" type="button"><?php esc_html_e('Add date schedule', 'add-coupon-by-link-woocommerce'); ?></button>
    <div class="pi-date-schedules">
        <?php self::rows($date_schedule); ?>
    </div>
    <div class="mt-2">
    <p class="form-field">
    <label><?php esc_html_e('Invalid date warning message', 'add-coupon-by-link-woocommerce'); ?></label>
    <textarea name="pisol_aclw_date_based_scheduling_warning_msg" class="short"><?php echo esc_html($date_warning_msg); ?></textarea>
    </p>
</div>
</div><!-- End of date based scheduling container -->

<h2 style="border-top:1px solid #000; border-bottom:1px solid #000; text-align:center; font-size:20px; margin-top:20px;">OR</h2>

<div class="pi-display-flex mt-2 ">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling_enabled" id="pisol_aclw_day_based_scheduling_enabled" value="yes" <?php echo !empty($day_based_scheduling_enabled) ? 'checked' : ''; ?>  class="pi-radio-group">
    <strong><?php esc_html_e('Enable day based scheduling', 'add-coupon-by-link-woocommerce'); ?></strong>
</div>
<div id="pi-day-based-scheduling-container" class="free-version">

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[0][enabled]" value="1" <?php echo !empty($day_based_scheduling[0]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Sunday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[0][from]" value="<?php echo esc_attr($day_based_scheduling[0]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[0][to]" value="<?php echo esc_attr($day_based_scheduling[0]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[1][enabled]" value="1" <?php echo !empty($day_based_scheduling[1]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Monday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[1][from]" value="<?php echo esc_attr($day_based_scheduling[1]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[1][to]" value="<?php echo esc_attr($day_based_scheduling[1]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[2][enabled]" value="1" <?php echo !empty($day_based_scheduling[2]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Tuesday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[2][from]" value="<?php echo esc_attr($day_based_scheduling[2]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[2][to]" value="<?php echo esc_attr($day_based_scheduling[2]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[3][enabled]" value="1" <?php echo !empty($day_based_scheduling[3]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Wednesday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[3][from]" value="<?php echo esc_attr($day_based_scheduling[3]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[3][to]" value="<?php echo esc_attr($day_based_scheduling[3]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[4][enabled]" value="1" <?php echo !empty($day_based_scheduling[4]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Thursday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[4][from]" value="<?php echo esc_attr($day_based_scheduling[4]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[4][to]" value="<?php echo esc_attr($day_based_scheduling[4]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[5][enabled]" value="1" <?php echo !empty($day_based_scheduling[5]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Friday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[5][from]" value="<?php echo esc_attr($day_based_scheduling[5]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[5][to]" value="<?php echo esc_attr($day_based_scheduling[5]['to'] ?? ''); ?>">
    </div>
</div>

<div class="pi-display-flex mt-2">
    <input type="checkbox" name="pisol_aclw_day_based_scheduling[6][enabled]" value="1" <?php echo !empty($day_based_scheduling[6]['enabled']) ? 'checked' : ''; ?>>
    <strong class="pi-days-name"><?php esc_html_e('Saturday', 'add-coupon-by-link-woocommerce'); ?></strong>
    <div class="pi-display-flex">
        <span class="mx-2">from</span>
        <input type="time" class="form-control d-inline w-auto" name="pisol_aclw_day_based_scheduling[6][from]" value="<?php echo esc_attr($day_based_scheduling[6]['from'] ?? ''); ?>">
        <span class="mx-2">to</span>
        <input type="time" class="form-control d-inline w-auto"name="pisol_aclw_day_based_scheduling[6][to]" value="<?php echo esc_attr($day_based_scheduling[6]['to'] ?? ''); ?>">
    </div>
</div>

<div class="mt-2">
    <p class="form-field">
    <label><?php esc_html_e('Invalid days warning message', 'add-coupon-by-link-woocommerce'); ?></label>
    <textarea name="pisol_aclw_day_based_scheduling_warning_msg" class="short"><?php echo esc_html($warning_msg); ?></textarea>
    </p>
</div>
</div>
</div>