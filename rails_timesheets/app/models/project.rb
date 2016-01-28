class Project < ActiveRecord::Base
  validates_presence_of :name
  validates_uniqueness_of :name
  validates_length_of :name, :minimum => 4
  has_and_belongs_to_many :users
  has_many :tasks
end
