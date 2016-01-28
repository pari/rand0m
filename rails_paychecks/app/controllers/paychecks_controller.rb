class PaychecksController < ApplicationController
  layout "homescreen"
  before_filter :checkvaliduser , :except => :nothing
  
  def index
    @npaycheck = Paycheck.new
    @listOfPaychecks = Paycheck.find(:all) 
    @listOfEmployees = Employee.find(:all)
    @listPaychecks_string = "List of Paychecks"
  end
  
  
  
  
  
  def show
    @this_PayCheck = Paycheck.find(params[:id])
    @returnCheck = @this_PayCheck.returnchecks.new
  end
  
  
  
  
  
  def new # not used at the moment
    @npaycheck = Paycheck.new
    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @npaycheck }
    end
  end
  
  
  
  def edit # not used at the moment
    @paycheck = Employee.find(params[:id])
  end
  
  
  
  
  
  def create
    @npaycheck = Paycheck.new(params[:paycheck])
    
    if @npaycheck.save
        flash[:notice] = "New Paycheck added !"
        redirect_to :action => "index"
    else
        flash[:notice] = "Error adding Paycheck"
          @listOfEmployees = Employee.find(:all) 
          @listOfPaychecks = Paycheck.find(:all)
          render :action => 'index'
    end
  end
  
  
  
  
  
  def update # not used at the moment
    @paycheck = Paycheck.find(params[:id]) 
  end
  
  
  
  
  
  def destroy
    Paycheck.find(params[:id]).destroy()
    flash[:notice] = "Paycheck deleted successfully !"
    redirect_to :action => "index"
  end
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  # custom methods follows .. all above all CRUD/REST standard methods
  def emphistory
    @npaycheck = Paycheck.new
    @selectedEmployee = Employee.find(params[:id])
    @listOfPaychecks = @selectedEmployee.paychecks.find(:all) 
    @listOfEmployees = Employee.find(:all)
    @listPaychecks_string = "List of Paychecks for " + @selectedEmployee.name
    render "index"
  end
  
  
  def createrc
    @returnCheck = Paycheck.find(params[:id]).returnchecks.new(params[:returncheck])
    
    if @returnCheck.save
        flash[:notice] = "New Return Paycheck added !"
        redirect_to :action => "show", :id => params[:id]
    else
        flash[:notice] = "Error adding Return Check"
        @returnCheck = Paycheck.find(params[:id]).returnchecks.new
        render :action => 'addreturncheck', :id => params[:id]
    end
  end
  
  
  def editrc # edit return check
      @returnCheck = Returncheck.find(params[:id])
      @this_PayCheck = @returnCheck.paycheck
  end
  
  
  
  def update_rc 
    @returnCheck = Returncheck.find(params[:id])
    if @returnCheck.update_attributes(params[:returncheck])
      redirect_to :action => "show", :id => @returnCheck.paycheck_id
    else
      @this_PayCheck = @returnCheck.paycheck
      render :action => 'editrc', :id => params[:id]
    end
  end
  
  
end
