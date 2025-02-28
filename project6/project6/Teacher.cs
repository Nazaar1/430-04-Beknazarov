using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace project6
{
    public class Teacher
    {
        public string LastName { get; set; }
        public string FirstName { get; set; }
        public string Patronymic { get; set; }
        public string Discip_Code { get; set; }
        public string Discip_Name { get; set; }

        public override string ToString()
        {
            return $"{LastName};{FirstName};{Patronymic};{Discip_Code};{Discip_Name}";
        }
    }
}
