# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_klmtracks_session',
  :secret      => 'ae84a400bb6dc301588dd1e2e03836ae490b0b3d5abead3a4894dbb7be84cd03d544aeea79c9627e7a4affb3b1178cfe5401dada31fc8ef48c58013549bb1b57'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
