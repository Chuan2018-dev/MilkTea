ALTER TABLE `users`
  ADD COLUMN `api_token_hash` varchar(64) DEFAULT NULL AFTER `remember_token`,
  ADD COLUMN `api_token_created_at` timestamp NULL DEFAULT NULL AFTER `api_token_hash`;
