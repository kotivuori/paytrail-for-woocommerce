<?php

/**
 * Updated Provider form view
 */

// Ensure that the file is being run within the WordPress context.
if (! defined('ABSPATH')) {
    die;
}
$allowed_html = array(
    'a' => array(
        'href'  => array(),
        'title' => array(),
    )
);

// Enqueue styles and scripts.
wp_enqueue_style('paytrail-woocommerce-updated-payment-fields');
wp_enqueue_script('paytrail-woocommerce-updated-payment-fields');

// Something went wrong loading the providers.
if (! empty($data['error'])) {
    printf(
        '<p class="paytrail-for-woocommerce-payment-fields__error">%s</p>',
        esc_html($data['error'])
    );
    return;
}

// Terms
$terms_link = $data['terms'];
?>
<div class="paytrail-payment-container" role="region" aria-label="<?php echo esc_attr__('Paytrail payment Methods', 'paytrail-for-woocommerce'); ?>">
    <?php if (! \Paytrail\WooCommercePaymentGateway\Helper::getIsSubscriptionsEnabled()) : ?>

        <?php foreach ($data['groups'] as $group) : ?>
            <?php if ($group['id'] === 'bank' && isset($data['groups'][1])) : ?>
                <div class="paytrail-group" role="group">
                    <div class="paytrail-group__header"><?= esc_html($group['name']); ?></div>
                    <!-- Bank dropdown: visible by default -->
                    <div class="paytrail-bank-dropdown" role="button" tabindex="0" aria-expanded="false" aria-controls="paytrail-bank-expanded">
                        <p class="paytrail-bank-dropdown__text hidden">
                            <?php _e('Select your bank', 'paytrail-for-woocommerce'); ?>
                        </p>
                        <div class="paytrail-bank-dropdown__providers">
                            <?php foreach ($group['providers'] as $provider) : ?>
                                <img src="<?= esc_url($provider->getSvg()); ?>"
                                    title="<?= esc_attr($provider->getName()); ?>"
                                    alt="<?= esc_attr($provider->getName()); ?>">
                            <?php endforeach; ?>
                        </div>
                        <span class="paytrail-bank-dropdown__toggle-btn" aria-hidden="true">
                            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" aria-hidden="true">
                                <path d="M9.88 0.29L6 4.17L2.12 0.29C1.73 -0.0999996 1.1 -0.0999996 0.709996 0.29C0.319996 0.68 0.319996 1.31 0.709996 1.7L5.3 6.29C5.69 6.68 6.32 6.68 6.71 6.29L11.3 1.7C11.69 1.31 11.69 0.68 11.3 0.29C10.91 -0.0899996 10.27 -0.0999996 9.88 0.29Z" fill="currentColor"></path>
                                <path d="M9.88 0.29L6 4.17L2.12 0.29C1.73 -0.0999996 1.1 -0.0999996 0.709996 0.29C0.319996 0.68 0.319996 1.31 0.709996 1.7L5.3 6.29C5.69 6.68 6.32 6.68 6.71 6.29L11.3 1.7C11.69 1.31 11.69 0.68 11.3 0.29C10.91 -0.0899996 10.27 -0.0999996 9.88 0.29Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </div>

                    <!-- Expanded bank buttons view: hidden by default -->
                    <ul id="paytrail-bank-expanded" class="paytrail-group__providers hidden" aria-label="<?php echo esc_attr__('Bank Selection', 'paytrail-for-woocommerce'); ?>">
                        <?php foreach ($group['providers'] as $provider) : ?>
                            <li class="paytrail-group__provider">
                                <label for="<?= $provider->getId(); ?>" aria-label="<?= esc_attr(sprintf(__('Pay with %s', 'paytrail-for-woocommerce'), $provider->getName())); ?>">
                                    <img
                                        src="<?= esc_url($provider->getSvg()); ?>"
                                        title="<?= esc_attr($provider->getName()); ?>"
                                        alt="<?= esc_attr($provider->getName()); ?>"
                                        aria-hidden="true">
                                    <input class="paytrail-group__provider__input" type="radio" id="<?= $provider->getId(); ?>" name="payment_provider" value="<?= esc_attr($provider->getId()); ?>" tabindex="-1">
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else : ?>
                <?php
                // Render other groups and bank group if no other groups are present
                $groupedProviders = [];
                $groupedProviders[$group['id']] = [
                    'name'      => $group['name'],
                    'providers' => $group['providers'],
                ];
                ?>
                <?php foreach ($groupedProviders as $groupId => $groupData) : ?>
                    <?php
                    // Initialize credit card counter
                    $creditcardCount = 1;
                    ?>
                    <div class="paytrail-group" role="group">
                        <div class="paytrail-group__header"><?= esc_html($groupData['name']); ?></div>
                        <ul class="paytrail-group__providers" aria-label="<?= esc_attr__(sprintf(__('%s selection', 'paytrail-for-woocommerce'), $groupData['name'])); ?>">
                            <?php foreach ($groupData['providers'] as $provider) :
                                // For credit card providers, a unique ID is appended.
                                if ($provider->getId() === 'creditcard') {
                                    $provider_id = esc_attr($provider->getId()) . '-' . $creditcardCount;
                                    $creditcardCount++;
                                } else {
                                    $provider_id = esc_attr($provider->getId());
                                }
                            ?>
                                <li class="paytrail-group__provider">
                                    <label for="<?= $provider_id; ?>" aria-label="<?= esc_attr(sprintf(__('Pay with %s', 'paytrail-for-woocommerce'), $provider->getName())); ?>">
                                        <img
                                            src="<?= esc_url($provider->getSvg()); ?>"
                                            title="<?= esc_attr($provider->getName()); ?>"
                                            alt="<?= esc_attr($provider->getName()); ?>"
                                            aria-hidden="true">
                                        <input class="paytrail-group__provider__input" type="radio" id="<?= $provider_id; ?>" name="payment_provider" value="<?= esc_attr($provider->getId()); ?>">
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (is_user_logged_in() && 'creditcard' == $groupId) : ?>
                            <?php (new \Paytrail\WooCommercePaymentGateway\Gateway())->render_saved_payment_methods(); ?>
                        <?php elseif (get_option('users_can_register') == 1 && 'creditcard' == $groupId) : ?>
                            <?php
                            $mypage_link = get_permalink(wc_get_page_id('myaccount'));
                            echo '<p class="paytrail-add-card-login-description" role="note">';
                            echo sprintf(__('To save your card details for next time, <a href="%s">log in to the store or create an account.</a>'), esc_html($mypage_link));
                            echo '</p>';
                            ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="checkout-terms-link" role="complementary"><?php echo wp_kses($terms_link, $allowed_html); ?></div>
    <?php endif; ?>

    <!-- Subscription payment methods -->
    <?php if (\Paytrail\WooCommercePaymentGateway\Helper::getIsSubscriptionsEnabled() && ! is_user_logged_in() && get_option('users_can_register') == 1) : ?>
        <?php
        $mypage_link = get_permalink(wc_get_page_id('myaccount'));
        echo '<p class="paytrail-add-card-login-description" role="alert">';
        echo sprintf(__('Please <a href="%s">log in</a> or sign up below to pay for your order.', 'paytrail-for-woocommerce'), esc_html($mypage_link));
        echo '</p>';
        ?>
    <?php elseif (\Paytrail\WooCommercePaymentGateway\Helper::getIsSubscriptionsEnabled() && is_user_logged_in()) : ?>
        <?php (new \Paytrail\WooCommercePaymentGateway\Gateway())->render_saved_payment_methods(); ?>
    <?php endif; ?>
</div>

<script>
    if (typeof initPaytrail === 'function') {
        initPaytrail();
    }
</script>
