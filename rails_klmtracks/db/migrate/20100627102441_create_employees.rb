class CreateEmployees < ActiveRecord::Migration
  def self.up
    create_table :employees do |t|
      t.string :username
      t.string :fullname
      t.string :email
      t.string :empid
      t.string :crypted_password  
      t.string :password_salt  
      t.string :persistence_token
      t.string :address
      t.integer :location_id
      t.integer :department_id
      t.timestamps
    end
  end

  def self.down
    drop_table :employees
  end
end
