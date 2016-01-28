# Filters added to this controller apply to all controllers in the application.
# Likewise, all the methods added will be available for all controllers.

class ApplicationController < ActionController::Base
  helper :all # include all helpers, all the time
  helper_method :current_user
  protect_from_forgery # See ActionController::RequestForgeryProtection for details

  # Scrub sensitive parameters from your log
  # filter_parameter_logging :password
  private
  def current_user  
    @current_user = current_user_session && current_user_session.record  
  end
  def current_user_session  
    return @current_user_session if defined?(@current_user_session)  
    @current_user_session = UserSession.find  
  end
  
  
  protected
  
  def checkvaliduser
    unless current_user
      flash[:notice] = 'Restricted Area'
      redirect_to :controller => 'user_sessions' , :action => 'new'
      return false
    end
  end
end
