CREATE TABLE IF NOT EXISTS `foodcar_merchant_shipping`
(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
    `order_id` INT UNSIGNED NOT NULL UNIQUE COMMENT '订单号',
    `shipping_time` INT UNSIGNED NOT NULL COMMENT '发货时间',
    `checked` TINYINT NOT NULL DEFAULT 0 COMMENT '是否确认发货，0为未确认'
)