-- Table admins
CREATE TABLE admins (
  id BIGSERIAL PRIMARY KEY,
  avatar VARCHAR(255) DEFAULT NULL,
  first_name VARCHAR(255) DEFAULT NULL,
  last_name VARCHAR(256) DEFAULT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  google2fa_secret TEXT,
  google2fa_enabled BOOLEAN NOT NULL DEFAULT FALSE,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table blogs
CREATE TABLE blogs (
  id BIGSERIAL PRIMARY KEY,
  category_id BIGINT NOT NULL,
  title TEXT NOT NULL,
  slug VARCHAR(255) NOT NULL,
  summary TEXT NOT NULL,
  content TEXT NOT NULL,
  author_id BIGINT NOT NULL,
  tag TEXT DEFAULT NULL,
  cover VARCHAR(255) DEFAULT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table blog_categories
CREATE TABLE blog_categories (
  id BIGSERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table cache
CREATE TABLE cache (
  key VARCHAR(255) PRIMARY KEY,
  value TEXT NOT NULL,
  expiration INT NOT NULL
);

-- Table cache_locks
CREATE TABLE cache_locks (
  key VARCHAR(255) PRIMARY KEY,
  owner VARCHAR(255) NOT NULL,
  expiration INT NOT NULL
);

-- Table component_contents
CREATE TABLE component_contents (
  id BIGSERIAL PRIMARY KEY,
  component_id INT NOT NULL,
  content TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table deposit_methods
CREATE TABLE deposit_methods (
  id BIGSERIAL PRIMARY KEY,
  payment_gateway_id INT DEFAULT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  type TEXT NOT NULL CHECK (type IN ('auto', 'manual')),
  method_code VARCHAR(255) NOT NULL,
  currency VARCHAR(255) NOT NULL,
  currency_symbol VARCHAR(255) NOT NULL,
  min_deposit DOUBLE PRECISION NOT NULL,
  max_deposit DOUBLE PRECISION NOT NULL,
  conversion_rate_live BOOLEAN DEFAULT NULL,
  conversion_rate DOUBLE PRECISION DEFAULT NULL,
  charge_type TEXT NOT NULL CHECK (charge_type IN ('fixed', 'percent')),
  charge DOUBLE PRECISION NOT NULL,
  fields TEXT,
  receive_payment_details TEXT,
  status BOOLEAN NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table failed_jobs
CREATE TABLE failed_jobs (
  id BIGSERIAL PRIMARY KEY,
  uuid VARCHAR(255) NOT NULL UNIQUE,
  connection TEXT NOT NULL,
  queue TEXT NOT NULL,
  payload TEXT NOT NULL,
  exception TEXT NOT NULL,
  failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Table jobs
CREATE TABLE jobs (
  id BIGSERIAL PRIMARY KEY,
  queue VARCHAR(255) NOT NULL,
  payload TEXT NOT NULL,
  attempts BOOLEAN NOT NULL,
  reserved_at INT DEFAULT NULL,
  available_at INT NOT NULL,
  created_at INT NOT NULL
);

CREATE INDEX jobs_queue_index ON jobs (queue);

-- Table job_batches
CREATE TABLE job_batches (
  id VARCHAR(255) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  total_jobs INT NOT NULL,
  pending_jobs INT NOT NULL,
  failed_jobs INT NOT NULL,
  failed_job_ids TEXT NOT NULL,
  options TEXT DEFAULT NULL,
  cancelled_at INT DEFAULT NULL,
  created_at INT NOT NULL,
  finished_at INT DEFAULT NULL
);

-- Table languages
CREATE TABLE languages (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  is_default BOOLEAN NOT NULL DEFAULT FALSE,
  is_rtl BOOLEAN NOT NULL DEFAULT FALSE,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table tickets
CREATE TABLE tickets (
  id BIGSERIAL PRIMARY KEY,
  uuid VARCHAR(255) NOT NULL UNIQUE,
  user_id BIGINT NOT NULL,
  category_id BIGINT DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  attachment VARCHAR(255) DEFAULT NULL,
  priority VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL,
  is_resolved BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX tickets_user_id_foreign ON tickets (user_id);

-- Table migrations
CREATE TABLE migrations (
  id SERIAL PRIMARY KEY,
  migration VARCHAR(255) NOT NULL,
  batch INT NOT NULL
);

-- Table permissions
CREATE TABLE permissions (
  id BIGSERIAL PRIMARY KEY,
  category VARCHAR(196) NOT NULL,
  name VARCHAR(255) NOT NULL,
  guard_name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX permissions_name_guard_name_unique ON permissions (name, guard_name);

-- Table navigations
CREATE TABLE navigations (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  page_id INT DEFAULT NULL,
  header_order INT NOT NULL DEFAULT 0,
  footer_order INT NOT NULL DEFAULT 0,
  display VARCHAR(255) NOT NULL DEFAULT 'header',
  target VARCHAR(255) NOT NULL DEFAULT '_self',
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table notifications
CREATE TABLE notifications (
  id UUID PRIMARY KEY,
  type VARCHAR(255) NOT NULL,
  notifiable_type VARCHAR(255) NOT NULL,
  notifiable_id BIGINT NOT NULL,
  data TEXT NOT NULL,
  read_at TIMESTAMP DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX notifications_notifiable_type_notifiable_id_index 
ON notifications (notifiable_type, notifiable_id);

-- Table notify_templates
CREATE TABLE notify_templates (
  id BIGSERIAL PRIMARY KEY,
  icon VARCHAR(256) DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(256) DEFAULT NULL,
  info VARCHAR(265) DEFAULT NULL,
  type VARCHAR(256) NOT NULL,
  subject VARCHAR(255) DEFAULT NULL,
  push_message TEXT,
  mail_message TEXT,
  variables TEXT,
  mail_status BOOLEAN NOT NULL DEFAULT FALSE,
  push_status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table services
CREATE TABLE services (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  price DOUBLE PRECISION NOT NULL,
  light_icon VARCHAR(255) DEFAULT NULL,
  dark_icon VARCHAR(255) DEFAULT NULL,
  cover VARCHAR(255) DEFAULT NULL,
  video_cover VARCHAR(255) DEFAULT NULL,
  video_link VARCHAR(255) DEFAULT NULL,
  description TEXT NOT NULL,
  faq_content TEXT,
  side_content TEXT,
  tags VARCHAR(255) DEFAULT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX services_slug_unique ON services (slug);

-- Table pages
CREATE TABLE pages (
  id BIGSERIAL PRIMARY KEY,
  is_breadcrumb BOOLEAN NOT NULL DEFAULT TRUE,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  component_id TEXT NOT NULL,
  type VARCHAR(255) NOT NULL DEFAULT 'dynamic',
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description VARCHAR(255) DEFAULT NULL,
  meta_keywords VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX pages_slug_unique ON pages (slug);

-- Table page_components
CREATE TABLE page_components (
  id BIGSERIAL PRIMARY KEY,
  content_id BIGINT DEFAULT NULL,
  icon VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  section VARCHAR(255) NOT NULL,
  category VARCHAR(196) NOT NULL DEFAULT 'dynamic',
  content TEXT NOT NULL,
  type VARCHAR(255) NOT NULL DEFAULT 'static',
  content_fields TEXT,
  with_modal BOOLEAN NOT NULL DEFAULT TRUE,
  status VARCHAR(255) NOT NULL DEFAULT '1',
  sort INT NOT NULL DEFAULT 60,
  preview VARCHAR(196) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Add comment for the content_fields column
COMMENT ON COLUMN page_components.content_fields IS 'This field is for component contents table';

-- Table password_reset_tokens
CREATE TABLE password_reset_tokens (
  email VARCHAR(255) NOT NULL,
  token VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (email)
);

-- Table payment_gateways
CREATE TABLE payment_gateways (
  id BIGSERIAL PRIMARY KEY,
  logo VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  currencies TEXT NOT NULL,
  credentials TEXT NOT NULL,
  ipn BOOLEAN DEFAULT FALSE,
  is_withdraw VARCHAR(255) DEFAULT NULL,
  status BOOLEAN NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Add comments for columns
COMMENT ON COLUMN payment_gateways.code IS 'Code for payment gateway e.g. paypal, stripe, razorpay';
COMMENT ON COLUMN payment_gateways.currencies IS 'Json encoded currencies';
COMMENT ON COLUMN payment_gateways.credentials IS 'Json encoded credentials';
COMMENT ON COLUMN payment_gateways.ipn IS 'IPN (Instant Payment Notification) is a real-time, server-to-server notification';

-- Table model_has_permissions
CREATE TABLE model_has_permissions (
  permission_id BIGINT NOT NULL,
  model_type VARCHAR(255) NOT NULL,
  model_id BIGINT NOT NULL,
  PRIMARY KEY (permission_id, model_id, model_type),
  FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE
);

CREATE INDEX model_has_permissions_model_id_model_type_index 
ON model_has_permissions (model_id, model_type);

-- Table roles
CREATE TABLE roles (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  guard_name VARCHAR(255) NOT NULL,
  description VARCHAR(196) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX roles_name_guard_name_unique ON roles (name, guard_name);

-- Table role_has_permissions
CREATE TABLE role_has_permissions (
  permission_id BIGINT NOT NULL,
  role_id BIGINT NOT NULL,
  PRIMARY KEY (permission_id, role_id)
);

CREATE INDEX role_has_permissions_role_id_foreign ON role_has_permissions (role_id);

-- Table personal_access_tokens
CREATE TABLE personal_access_tokens (
  id BIGSERIAL PRIMARY KEY,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL,
  abilities TEXT,
  last_used_at TIMESTAMP DEFAULT NULL,
  expires_at TIMESTAMP DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX personal_access_tokens_token_unique ON personal_access_tokens (token);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON personal_access_tokens (tokenable_type, tokenable_id);

-- Table plans
CREATE TABLE plans (
  id BIGSERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  icon VARCHAR(255) NOT NULL,
  background_shape VARCHAR(196) NOT NULL,
  monthly_price DOUBLE PRECISION NOT NULL,
  yearly_price DOUBLE PRECISION NOT NULL,
  badge VARCHAR(255) DEFAULT NULL,
  features TEXT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table plugins
CREATE TABLE plugins (
  id BIGSERIAL PRIMARY KEY,
  type VARCHAR(200) NOT NULL,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(196) DEFAULT NULL,
  fields_blade TEXT,
  credentials TEXT NOT NULL,
  logo VARCHAR(255) NOT NULL,
  description VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL,
  copy_url TEXT,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table model_has_roles
CREATE TABLE model_has_roles (
  role_id BIGINT NOT NULL,
  model_type VARCHAR(255) NOT NULL,
  model_id BIGINT NOT NULL,
  PRIMARY KEY (role_id, model_id, model_type),
  FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
);

CREATE INDEX model_has_roles_model_id_model_type_index 
ON model_has_roles (model_id, model_type);

ALTER TABLE role_has_permissions
  ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE,
  ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE;

-- Table transactions
CREATE TABLE transactions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL,
  txid VARCHAR(255) DEFAULT NULL,
  description TEXT,
  method VARCHAR(255) DEFAULT NULL,
  method_type VARCHAR(196) DEFAULT NULL, -- 'auto', 'manual', 'system'
  type VARCHAR(255) NOT NULL,
  amount DECIMAL(15,2) NOT NULL,
  fee DECIMAL(15,2) DEFAULT NULL,
  currency VARCHAR(255) NOT NULL DEFAULT 'USD',
  pay_amount VARCHAR(255) DEFAULT NULL,
  pay_currency VARCHAR(255) DEFAULT NULL,
  array_data TEXT,
  action_cause TEXT,
  status VARCHAR(255) NOT NULL CHECK (status IN ('pending', 'completed', 'failed')),
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX transactions_user_id_foreign ON transactions (user_id);

-- Table sessions
CREATE TABLE sessions (
  id VARCHAR(255) NOT NULL PRIMARY KEY,
  user_id BIGINT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent TEXT,
  payload TEXT NOT NULL,
  last_activity INT NOT NULL
);

-- Table settings
CREATE TABLE settings (
  id BIGSERIAL PRIMARY KEY,
  key VARCHAR(255) NOT NULL,
  val VARCHAR(255) NOT NULL,
  type VARCHAR(255) NOT NULL DEFAULT 'string',
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX settings_key_unique ON settings (key);

-- Table site_customs
CREATE TABLE site_customs (
  id BIGSERIAL PRIMARY KEY,
  type VARCHAR(255) NOT NULL,
  value TEXT NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX site_customs_key_unique ON site_customs (type);

-- Table socials
CREATE TABLE socials (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  icon_class VARCHAR(255) NOT NULL,
  target VARCHAR(255) NOT NULL,
  url VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table subscribers
CREATE TABLE subscribers (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX subscribers_email_unique ON subscribers (email);

-- Table subscriptions
CREATE TABLE subscriptions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL,
  plan_id BIGINT NOT NULL,
  transaction_id BIGINT NOT NULL,
  duration_type VARCHAR(196) DEFAULT NULL,
  start_date DATE NOT NULL,
  expiry_date DATE NOT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX subscriptions_user_id_foreign ON subscriptions (user_id);
CREATE INDEX subscriptions_plan_id_foreign ON subscriptions (plan_id);

-- Table support_categories
CREATE TABLE support_categories (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table users
CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  avatar VARCHAR(255) DEFAULT NULL,
  first_name VARCHAR(255) DEFAULT NULL,
  last_name VARCHAR(255) DEFAULT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP DEFAULT NULL,
  phone VARCHAR(255) DEFAULT NULL UNIQUE,
  address TEXT,
  city VARCHAR(255) DEFAULT NULL,
  state VARCHAR(255) DEFAULT NULL,
  country VARCHAR(255) DEFAULT NULL,
  zip VARCHAR(255) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  google2fa_secret TEXT,
  google2fa_enabled BOOLEAN NOT NULL DEFAULT FALSE,
  balance DOUBLE PRECISION NOT NULL DEFAULT 0,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  provider VARCHAR(196) DEFAULT NULL,
  provider_id VARCHAR(196) DEFAULT NULL,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

-- Table orders
CREATE TABLE orders (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL,
  service_id BIGINT NOT NULL,
  transaction_id BIGINT NOT NULL,
  order_number VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE UNIQUE INDEX orders_order_number_unique ON orders (order_number);
CREATE INDEX orders_user_id_foreign ON orders (user_id);
CREATE INDEX orders_service_id_foreign ON orders (service_id);
CREATE INDEX orders_transaction_id_foreign ON orders (transaction_id);

ALTER TABLE orders
  ADD CONSTRAINT orders_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id),
  ADD CONSTRAINT orders_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES transactions (id),
  ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id);

-- Table messages
CREATE TABLE messages (
  id BIGSERIAL PRIMARY KEY,
  admin_id BIGINT REFERENCES admins (id),
  ticket_id BIGINT NOT NULL REFERENCES tickets (id),
  message TEXT NOT NULL,
  attachment VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX messages_admin_id_foreign ON messages (admin_id);
CREATE INDEX messages_ticket_id_foreign ON messages (ticket_id);

-- Table tasks
CREATE TABLE tasks (
  id BIGSERIAL PRIMARY KEY,
  order_id BIGINT NOT NULL,
  assigned_to TEXT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  attachment VARCHAR(255) DEFAULT NULL,
  progress VARCHAR(255) NOT NULL DEFAULT '0',
  start_date DATE DEFAULT NULL,
  due_date DATE DEFAULT NULL,
  priority VARCHAR(255) NOT NULL DEFAULT 'low',
  status VARCHAR(255) NOT NULL DEFAULT 'pending',
  info_fields TEXT,
  created_at TIMESTAMP DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT NULL
);

CREATE INDEX tasks_order_id_foreign ON tasks (order_id);

ALTER TABLE tasks
  ADD CONSTRAINT tasks_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders (id);
