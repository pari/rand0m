class Employee < ActiveRecord::Base
  has_many :paychecks
end
