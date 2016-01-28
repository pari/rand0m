class CreateTrDetails < ActiveRecord::Migration
  def self.up
    create_table :tr_details do |t|
      t.integer :coupon_price_id
      t.integer :qty
      t.integer :transaction_id

      t.timestamps
    end
  end

  def self.down
    drop_table :tr_details
  end
end
