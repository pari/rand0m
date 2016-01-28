class User < ActiveRecord::Base
  acts_as_authentic
  has_and_belongs_to_many :projects
  has_many :tasks
  
  def peers
    mypeers = []
    self.projects.each do |tp|
      tp.users.each do |tm|
        mypeers << tm unless mypeers.include? tm
      end
    end
    return mypeers
  end
end
