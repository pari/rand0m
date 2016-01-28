class ProjectController < ApplicationController
  layout "admin"
  before_filter :checkadmin , :except => :nothing
  
	def index
		@projects = Project.find(:all)
		
		respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @projects }
      format.json  { render :json => @projects }
    end
	end

	def new
		@nproject = Project.new
	end

	def create
		@nproject = Project.new(params[:newproject])
		if @nproject.save
		  flash[:notice] = "Project Created"
		  redirect_to :action => "index"
		else
		  render :action => "new"
		end
	end

	def edit
		@selectedProject = Project.find(params[:id])
	end
	
	def delete
    Project.find(params[:id]).destroy()
	  flash[:notice] = "Deleted selected Project"
		redirect_to :action => "index"
	end

  def update
    @selectedProject = Project.find(params[:id])
    if @selectedProject.update_attributes(params[:editproject])
      flash[:notice] = "Project details Updated !"
      redirect_to :action => "index"
    else
      render :action => 'edit'
    end
  end
  
  def nothing
    render :partial => "users/login"
  end
  
end
