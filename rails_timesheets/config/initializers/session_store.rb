# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_timesheets_session',
  :secret      => '277e7f5a8d4bd4ef52ff84b012cbb0bc0067d469d669a915363d097dbe1b13c7f4c99659b22320be84bacf2e7608987f328944ebdf5d686d7ea8a778934e32eb'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
