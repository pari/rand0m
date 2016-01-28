class Department < ActiveRecord::Base
  belongs_to :section
  has_many :employees
  validates_presence_of :name
  validates_uniqueness_of :name, :scope => [:section_id]
  
end
