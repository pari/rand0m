class LocationsController < ApplicationController
  layout "homescreen"
  
  def index
    @locations = Location.find(:all)
  end
  
  def new
    @location = Location.new
  end
  
  
  def create
    @location = Location.new(params[:location])
    if @location.save
      flash[:notice] = "Location Created"
      redirect_to :action => "index"
    else
        render :action => "new"
    end
  end
  

end
