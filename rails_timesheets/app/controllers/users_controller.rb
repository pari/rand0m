class UsersController < ApplicationController
  layout "admin"
  before_filter :checkadmin , :except => [:nothing , :adminlogin]
  
  def index
    @users = User.find(:all)
  end




  def new
    @user = User.new  
  end



  def edit
    @selectedUser = User.find(params[:id])
  end



  def update
    params[:edituser][:project_ids] ||= []
    params[:edituser][:isclient] ||= 0
    @selectedUser = User.find(params[:id])
    if @selectedUser.update_attributes(params[:edituser])
      flash[:notice] = "User details Updated !"
      redirect_to :action => "index"
    else
      render :action => 'edit'
    end
  end




  def create_user  
    params[:user][:isclient] ||= 0
    @user = User.new(params[:user])
    if @user.save  
      flash[:notice] = "New User account created successfully !"
      redirect_to :action => "index"
    else  
      render :action => 'new'  
    end  
  end





  def delete
    User.find(params[:id]).destroy()
    flash[:notice] = "User account deleted successfully !"
    redirect_to :action => "index"
  end



  def nothing
    render :partial => "users/login"
  end


  def adminlogin
    if params[:login][:admin_uname] == 'admin' && params[:login][:admin_pwd] == 'topsecret'
      session[:admin] = true
      flash[:notice] = "welcome !"
      redirect_to :action => "index"
    else
      session[:admin] = false
      flash[:notice] = "invalid username or password !"
      redirect_to :action => "nothing"
    end
  end
  
  
  
  def logout
    session[:admin] = false
    flash[:notice] = "logged out !"
    redirect_to :action => "nothing"
  end
  
  
end
