class ManagedivisionsController < ApplicationController
  layout "homescreen"
  
  def index
    @listOfDivisions = Division.find(:all)
    
    @ndivision = Division.new
    @nsection = Section.new
    @nDepartment = Department.new
    @sectionsInThisDiv = []
  end
  
  def create_division
    @ndivision = Division.new(params[:division])
    if @ndivision.save
      flash[:notice] = "Division Created"
      redirect_to :action => "index"
    else
      @nsection = Section.new
      @nDepartment = Department.new
      @sectionsInThisDiv = []
      flash[:notice] = "There was an error adding division"
      @listOfDivisions = Division.find(:all)
      render :action => "index"
    end

  end
  
  
  def create_section
    @nsection = Section.new(params[:section])
    if @nsection.save
      flash[:notice] = "Section Created"
    else
      flash[:notice] = "There was an error adding section"
    end
    redirect_to :action => "index"
  end
  
  
  
  def create_department
    @nDepartment = Department.new(params[:department])
    if @nDepartment.save
      flash[:notice] = "Department Created"
    else
      flash[:notice] = "There was an error adding department"
    end
    redirect_to :action => "index"
  end
  
  
  def getListOfSections
    render :update do |page|
          @sectionsInThisDiv = Division.find( params[:divid] ).sections
          page.replace_html 'div_sections_in_division', :partial => 'sectionsindivision', :object => @sectionsInThisDiv
    end
  end
  
end
