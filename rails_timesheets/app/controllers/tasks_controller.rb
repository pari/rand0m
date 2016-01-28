class TasksController < ApplicationController
  layout "tasks"
  before_filter :checkvaliduser # , :except => [:nothing , :adminlogin]
  
  def index
    @tasks = []
    @myprojects = current_user().projects.each do |tp|
      tp.tasks.find(:all).each do |task|
        @tasks << task
      end
    end
  end

  def new
    if current_user().isclient
      redirect_to :action => "index"
    end
    @task = Task.new  
  end

  def edit
    if current_user().isclient
      redirect_to :action => "index"
    end
    @edittask = current_user().tasks.find(params[:id])
  end

  def update
    @edittask = current_user().tasks.find(params[:id])
    if @edittask.update_attributes(params[:edittask])
      flash[:notice] = "Task details Updated !"
      redirect_to :action => "index"
    else
      render :action => 'edit'
    end
  end


  def create
    @task = Task.new(params[:task])
    if @task.save  
      flash[:notice] = "New Task created successfully !"
      redirect_to :action => "index"
    else  
      render :action => 'new'  
    end  
  end


  def delete
    if current_user().isclient
      redirect_to :action => "index"
    end
    current_user().tasks.find(params[:id]).destroy()
    flash[:notice] = "Task deleted successfully !"
    redirect_to :action => "index"
  end


end
