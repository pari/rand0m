class CreateReturnchecks < ActiveRecord::Migration
  def self.up
    create_table :returnchecks do |t|
      t.date :checkdate
      t.float :amount
      t.string :infavorof
      t.string :checknumber
      t.references :paycheck
      t.timestamps
    end
  end

  def self.down
    drop_table :returnchecks
  end
end
