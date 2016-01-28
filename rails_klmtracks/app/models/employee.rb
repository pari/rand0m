class Employee < ActiveRecord::Base
  acts_as_authentic 
  belongs_to :department
  belongs_to :location
  has_many :commissions
  
  
  def name_and_showRoom
    toreturn = fullname + ' ('  + Location.find(location_id).name + ')'
  end
  
  
end
