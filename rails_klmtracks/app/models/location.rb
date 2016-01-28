class Location < ActiveRecord::Base
  validates_uniqueness_of :name
  validates_presence_of :name
  has_many :employees
end
