class EmployeesController < ApplicationController
  layout "homescreen"
  
  def index
    @employees = Employee.find(:all)
  end
  
  def new
    @nemployee = Employee.new
    @listOfDepartments = Department.find(:all)
    @listOfLocations = Location.find(:all)
  end
  
  
  def create
    @nemployee = Employee.new(params[:employee])
    @listOfDepartments = Department.find(:all)
    @listOfLocations = Location.find(:all)
    if @nemployee.save
      flash[:notice] = "New Employee Created"
      redirect_to :action => "index"
    else
      flash[:notice] = "Error creating New Employee"
      render :action => 'new'
    end
  end
  
  
  def edit
    @employee = Employee.find(params[:id])
    @listOfDepartments = Department.find(:all)
    @listOfLocations = Location.find(:all)
  end
  
  
  def update
    @employee = Employee.find(params[:id])
    @listOfDepartments = Department.find(:all)
    @listOfLocations = Location.find(:all)

    if @employee.update_attributes(params[:employee])
      flash[:notice] = "Employee details updated"
      redirect_to :action => "index"
    else
      flash[:notice] = "Error creating Updating Employee"
      render :action => 'edit'
    end
  end
  
  
end
