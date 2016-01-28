class Division < ActiveRecord::Base
  validates_uniqueness_of :name
  validates_presence_of :name
  has_many :sections, :dependent => :destroy
end
