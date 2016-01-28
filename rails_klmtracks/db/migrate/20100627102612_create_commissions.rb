class CreateCommissions < ActiveRecord::Migration
  def self.up
    create_table :commissions do |t|
      t.date :forday
      t.date :takenon
      t.integer :amount
      t.integer :employee_id
      t.timestamps
    end
  end

  def self.down
    drop_table :commissions
  end
end
