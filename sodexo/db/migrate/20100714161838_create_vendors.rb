class CreateVendors < ActiveRecord::Migration
  def self.up
    create_table :vendors do |t|
      t.string :name
      t.string :address
      t.string :phone1
      t.string :phone2
      t.boolean :isActive

      t.timestamps
    end
  end

  def self.down
    drop_table :vendors
  end
end
