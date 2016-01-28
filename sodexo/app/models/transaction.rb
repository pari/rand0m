class Transaction < ActiveRecord::Base
  belongs_to :trans, :polymorphic => true  
  has_many :tr_details
  has_many :coupon_prices, :through => :tr_details
end
