ALTER TABLE users
    ADD COLUMN provider VARCHAR(196) NULL,
ADD COLUMN provider_id VARCHAR(196) NULL;

-- Alter the table to add the 'ipn' column
ALTER TABLE payment_gateways
    ADD COLUMN ipn TINYINT(1) DEFAULT '0' COMMENT 'IPN (Instant Payment Notification) is a real-time, server-to-server notification';

-- Update existing data in the 'payment_gateways' table to set appropriate 'ipn' values
UPDATE payment_gateways SET ipn = 0 WHERE code IN ('paypal', 'mollie', 'perfectmoney');
UPDATE payment_gateways SET ipn = 1 WHERE code IN ('stripe', 'coinbase', 'paystack', 'flutterwave');

-- Insert data for Paystack and Flutterwave into the 'payment_gateways' table
INSERT INTO `payment_gateways` (`id`, `logo`, `name`, `code`, `currencies`, `credentials`, `ipn`, `is_withdraw`, `status`, `created_at`, `updated_at`)
VALUES
    (6, 'general/static/gateway/paystack.png', 'Paystack', 'paystack', '[\"NGN\", \"USD\", \"GBP\", \"EUR\", \"GHS\", \"KES\", \"ZAR\", \"UGX\", \"TZS\", \"RWF\"]', '{\"public_key\":\"pk_test_b5e4a4477cb7a0a897972a5ba5fc819acafbc638\",\"secret_key\":\"sk_test_434461a79ce3d904e004076eba06ab6a02665d57\",\"merchant_email\":\"coevs.dev@gmail.com\"}', 1, NULL, 1, '2024-09-03 01:15:41', '2024-11-05 22:51:57'),
    (7, 'general/static/gateway/flutterwave.png', 'Flutterwave', 'flutterwave', '[\"USD\", \"EUR\", \"GBP\", \"NGN\", \"GHS\", \"KES\", \"ZAR\", \"UGX\", \"TZS\", \"RWF\", \"CAD\", \"AUD\", \"JPY\", \"INR\"]', '{\"public_key\":\"FLWPUBK_TEST-9a294e81b66857f0f0f3e1f793d90e3f-X\",\"secret_key\":\"FLWSECK_TEST-ff0c925381c35872203637a5aa7a59d0-X\",\"encryption_key\":\"FLWSECK_TEST21afba65b376\"}', 1, NULL, 1, '2024-09-03 01:15:41', '2024-11-06 00:33:58');

ALTER TABLE tasks
    ADD COLUMN info_fields LONGTEXT NULL AFTER status;

ALTER TABLE plugins
    ADD COLUMN copy_url TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AFTER status;
INSERT INTO `plugins` (`id`, `type`, `name`, `code`, `fields_blade`, `credentials`, `logo`, `description`, `status`, `copy_url`, `created_at`, `updated_at`) VALUES
                                                                                                                                                                 (13, 'general', 'Google Auth', 'google-auth', NULL, '{\"google_client_id\":null,\"google_client_secret\":null}', 'general/static/plugins/google-auth.png', 'Google Sign-In manages the OAuth 2.0 flow and token lifecycle, simplifying your integration with Google APIs.\n', 0, '{\"callback_url\":\"auth/google/callback\"}', NULL, '2024-11-08 10:01:24'),
                                                                                                                                                                 (14, 'general', 'Facebook Auth', 'facebook-auth', NULL, '{\"app_id\":null,\"app_secret\":null}', 'general/static/plugins/fb-auth.png', 'Facebook login is a straightforward way to enable users to log in through Facebook OAuth', 0, '{\"callback_url\":\"auth/facebook/callback\"}', NULL, '2024-11-08 10:01:30');
