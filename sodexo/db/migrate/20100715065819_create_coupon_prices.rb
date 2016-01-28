class CreateCouponPrices < ActiveRecord::Migration
  def self.up
    create_table :coupon_prices do |t|
      t.integer :price

      t.timestamps
    end
  end

  def self.down
    drop_table :coupon_prices
  end
end
