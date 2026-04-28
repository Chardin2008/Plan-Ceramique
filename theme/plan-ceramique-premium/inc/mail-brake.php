<?php

function pcp_mail_brake_enabled(): bool
{
    $value = getenv('PCP_MAIL_BRAKE');

    return $value !== false && in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
}

function pcp_stop_outgoing_mail($preempt)
{
    if (!pcp_mail_brake_enabled()) {
        return $preempt;
    }

    return true;
}
add_filter('pre_wp_mail', 'pcp_stop_outgoing_mail', 1);
