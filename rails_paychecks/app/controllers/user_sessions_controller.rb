class UserSessionsController < ApplicationController
  
  
  
  def index
      if current_user
        redirect_to :controller => 'employees', :action=>'index'
      else
        redirect_to :action=>'new'
      end
  end

  def new
     @user_session = UserSession.new  
  end


  def create
    @user_session = UserSession.new(params[:user_session])
    if params[:user_session]['username'] == 'admin' && params[:user_session]['password'] == 'topsecret'
    
    else
        redirect_to :action=>'new'
        return
    end
    
    if @user_session.save
      flash[:notice] = "Successfully logged in."  
      redirect_to :controller => 'employees', :action=>'index'
      return
    else
      redirect_to :action=>'new'
      return
    end
  end



  def destroy  
    @user_session = UserSession.find  
    @user_session.destroy  
    flash[:notice] = "Successfully logged out."  
    redirect_to root_url  
  end


end
