# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_sodexo_session',
  :secret      => 'c9c0af39ab26fd73a12b1657e7d15dbd29eb42e4db675666ef3b95129409844b2ff89214f5da6574fcf5ef7030441dce8b95d0c4557465feaadc984ffbd5cdb9'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
