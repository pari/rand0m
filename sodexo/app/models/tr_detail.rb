class TrDetail < ActiveRecord::Base
  belongs_to :transaction
  belongs_to :coupon_price
end
