class CommissionsController < ApplicationController
  layout "homescreen"
  
  def index
    #@commissions = Commission.find(:all, :conditions => { :forday => , takenon => , amount => , employee_id=> })
    # :conditions => [ "BETWEEN ? AND ?", from, to]
    @ncommission = Commission.new
    @listOfEmployees = Employee.find(:all) 
    @listOfCommissions = Commission.find(:all)
  end
  
  def create
    @ncommission = Commission.new(params[:commission])
    if @ncommission.save
        flash[:notice] = "New Commission added !"
        redirect_to :action => "index"
    else
        flash[:notice] = "Error adding commission"
          @listOfEmployees = Employee.find(:all) 
          @listOfCommissions = Commission.find(:all)
          render :action => 'index'
    end
    
    
  end
  
  
  
  
  
  
  def edit
    
  end
  
  def update
    
  end
  
  def show_commisions_for_selected
    
  end
  
  
end
