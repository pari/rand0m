class Commission < ActiveRecord::Base
  belongs_to :employee
  validates_presence_of :employee_id
  validates_presence_of :amount
  validates_presence_of :forday
  
end
