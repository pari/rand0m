class Section < ActiveRecord::Base
  belongs_to :division
  has_many :departments, :dependent => :destroy
  
  validates_presence_of :name
  validates_uniqueness_of :name, :scope => [:division_id]
end
