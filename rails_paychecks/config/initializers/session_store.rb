# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_checks_session',
  :secret      => '7449d90e1b1591941b09cd10b31884b039104ad4fc2a27131dd124c486b5ec5d2ad6807a6f851b74552591a91507dd656aad11b0979564f1b87938e183fe4740'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
